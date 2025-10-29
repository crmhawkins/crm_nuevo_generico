<?php

namespace App\Extensions;

class FacturaeOrderFixExtension
{
    /**
     * Método llamado antes de exportar (requerido por la biblioteca)
     * No necesitamos hacer nada aquí, la corrección se hace en __onBeforeSign
     */
    public function __onBeforeExport()
    {
        // No necesitamos hacer nada aquí
    }

    /**
     * Método llamado después de firmar (requerido por la biblioteca)
     * No necesitamos modificar el XML después de firmar
     */
    public function __onAfterSign($xml)
    {
        // Simplemente devolver el XML sin modificar
        return $xml;
    }

    /**
     * Método para obtener datos adicionales de la extensión (requerido por la biblioteca)
     * Nuestra extensión no agrega datos adicionales al XML
     */
    public function __getAdditionalData()
    {
        // Devolver null o string vacío ya que no agregamos datos adicionales
        return null;
    }

    /**
     * Corrige el orden de los elementos XML antes de firmar
     * Orden requerido: Items -> TaxesOutputs -> InvoiceTotals
     */
    public function __onBeforeSign($xml)
    {
        try {
            // Si el XML está vacío o es null, devolverlo sin modificar
            if (empty($xml) || !is_string($xml)) {
                \Illuminate\Support\Facades\Log::warning('FacturaeOrderFixExtension: XML vacío o no es string');
                return $xml;
            }
            
            \Illuminate\Support\Facades\Log::info('FacturaeOrderFixExtension: Iniciando corrección del orden XML', [
                'xml_length' => strlen($xml),
                'xml_preview' => substr($xml, 0, 200)
            ]);
            
            // El XML recibido ya está completo pero sin declaración XML
            // Debe tener la estructura: <fe:Facturae>...</fe:Facturae>
            
            // El XML puede o no tener declaración XML, manejarlo
            $xmlClean = $xml;
            if (strpos($xmlClean, '<?xml') === 0) {
                $xmlClean = substr($xmlClean, strpos($xmlClean, '>') + 1);
                $xmlClean = trim($xmlClean);
            }
            
            // Asegurar que el XML está completo y válido
            if (empty($xmlClean) || strlen($xmlClean) < 10) {
                \Illuminate\Support\Facades\Log::warning('FacturaeOrderFixExtension: XML vacío o muy corto');
                return $xml;
            }
            
            // Intentar cargar el XML
            $dom = new \DOMDocument();
            $dom->preserveWhiteSpace = false;
            $dom->formatOutput = false;
            
            // Suprimir errores de XML mal formado para manejarlos nosotros
            libxml_use_internal_errors(true);
            $loaded = @$dom->loadXML($xmlClean);
            $errors = libxml_get_errors();
            libxml_clear_errors();
            
            if (!$loaded || !empty($errors)) {
                \Illuminate\Support\Facades\Log::warning('FacturaeOrderFixExtension: Error al cargar XML', [
                    'errors' => array_map(function($e) { return $e->message; }, $errors)
                ]);
                return $xml; // Devolver XML original si hay errores de parseo
            }

            // Registrar posibles namespaces de Facturae
            $xpath = new \DOMXPath($dom);
            $xpath->registerNamespace('fe', 'http://www.facturae.es/Facturae/2014/v3.2.1/Facturae');
            $xpath->registerNamespace('fe32', 'http://www.facturae.es/Facturae/2009/v3.2/Facturae');
            
            // Buscar el elemento Invoice con diferentes namespaces posibles
            $invoiceNodes = $xpath->query('//fe:Invoice');
            if ($invoiceNodes->length === 0) {
                $invoiceNodes = $xpath->query('//fe32:Invoice');
            }
            if ($invoiceNodes->length === 0) {
                // Intentar sin namespace (en caso de que el XML no tenga namespace declarado)
                $invoiceNodes = $xpath->query('//Invoice');
            }
            
            if ($invoiceNodes->length === 0) {
                return $xml; // No se encontró Invoice, devolver XML original
            }

            $invoiceElement = $invoiceNodes->item(0);

            // Buscar los elementos
            $itemsElement = null;
            $taxesOutputsElement = null;
            $invoiceTotalsElement = null;

            foreach ($invoiceElement->childNodes as $child) {
                if ($child->nodeType === XML_ELEMENT_NODE) {
                    $tagName = $child->localName ?? $child->nodeName;
                    
                    if ($tagName === 'Items') {
                        $itemsElement = $child;
                    } elseif ($tagName === 'TaxesOutputs') {
                        $taxesOutputsElement = $child;
                    } elseif ($tagName === 'InvoiceTotals') {
                        $invoiceTotalsElement = $child;
                    }
                }
            }

            // Obtener las posiciones actuales
            $itemsPosition = -1;
            $taxesOutputsPosition = -1;
            $totalsPosition = -1;
            $position = 0;
            
            foreach ($invoiceElement->childNodes as $child) {
                if ($child->nodeType === XML_ELEMENT_NODE) {
                    $tagName = $child->localName ?? $child->nodeName;
                    if ($tagName === 'Items') {
                        $itemsPosition = $position;
                    } elseif ($tagName === 'TaxesOutputs') {
                        $taxesOutputsPosition = $position;
                    } elseif ($tagName === 'InvoiceTotals') {
                        $totalsPosition = $position;
                    }
                    $position++;
                }
            }

            // El esquema requiere este orden exacto dentro de Invoice:
            // InvoiceIssueData -> Items -> TaxesOutputs -> InvoiceTotals
            // Pero la biblioteca genera: InvoiceIssueData -> TaxesOutputs -> InvoiceTotals -> Items
            
            // Siempre reordenar para asegurar el orden correcto según Facturae 3.2.1
            // El orden debe ser: InvoiceHeader -> InvoiceIssueData -> Items -> TaxesOutputs -> InvoiceTotals
            $necesitaReordenar = true; // Forzar siempre el reordenamiento para garantizar orden correcto
            
            // Buscar InvoiceIssueData como referencia
            $invoiceIssueDataElement = null;
            $invoiceIssueDataPosition = -1;
            $position = 0;
            
            foreach ($invoiceElement->childNodes as $child) {
                if ($child->nodeType === XML_ELEMENT_NODE) {
                    $tagName = $child->localName ?? $child->nodeName;
                    if ($tagName === 'InvoiceIssueData') {
                        $invoiceIssueDataPosition = $position;
                        $invoiceIssueDataElement = $child;
                        break;
                    }
                    $position++;
                }
            }
            
            // Según el error del validador, el esquema Facturae 3.2.1 requiere:
            // InvoiceHeader -> InvoiceIssueData -> TaxesOutputs -> Items -> InvoiceTotals
            // El error dice: "Expected is (TaxesOutputs)" donde encuentra "Items"
            // Esto significa que TaxesOutputs debe ir DESPUÉS de InvoiceIssueData y ANTES de Items
            
            // Verificar si realmente necesitamos reordenar
            $ordenCorrecto = false;
            if ($invoiceIssueDataPosition !== -1 && $itemsPosition !== -1 && $taxesOutputsPosition !== -1 && $totalsPosition !== -1) {
                // Verificar: InvoiceIssueData < TaxesOutputs < Items < InvoiceTotals
                if ($invoiceIssueDataPosition < $taxesOutputsPosition && 
                    $taxesOutputsPosition < $itemsPosition && 
                    $itemsPosition < $totalsPosition) {
                    $ordenCorrecto = true;
                }
            }
            
            if ($ordenCorrecto) {
                $necesitaReordenar = false;
                \Illuminate\Support\Facades\Log::info('FacturaeOrderFixExtension: El orden ya es correcto, no se requiere reordenamiento');
            }

            // El orden correcto según el error del validador es:
            // InvoiceHeader -> InvoiceIssueData -> TaxesOutputs -> Items -> InvoiceTotals
            // La biblioteca genera: InvoiceHeader -> InvoiceIssueData -> TaxesOutputs -> InvoiceTotals -> Items
            
            \Illuminate\Support\Facades\Log::info('FacturaeOrderFixExtension: Estado de elementos', [
                'items_position' => $itemsPosition,
                'taxesOutputs_position' => $taxesOutputsPosition,
                'totals_position' => $totalsPosition,
                'necesitaReordenar' => $necesitaReordenar,
                'tiene_items' => $itemsElement !== null,
                'tiene_taxesOutputs' => $taxesOutputsElement !== null,
                'tiene_invoiceTotals' => $invoiceTotalsElement !== null
            ]);
            
            if ($necesitaReordenar && $itemsElement && $invoiceTotalsElement) {
                \Illuminate\Support\Facades\Log::info('FacturaeOrderFixExtension: Iniciando reordenamiento');
                // Buscar InvoiceIssueData (debe estar antes de Items)
                $invoiceIssueDataElement = null;
                foreach ($invoiceElement->childNodes as $child) {
                    if ($child->nodeType === XML_ELEMENT_NODE) {
                        $tagName = $child->localName ?? $child->nodeName;
                        if ($tagName === 'InvoiceIssueData') {
                            $invoiceIssueDataElement = $child;
                            break;
                        }
                    }
                }

                // Guardar referencias de los elementos a mover
                $nodesToReorder = [];
                
                // Remover todos los elementos que necesitamos reordenar
                if ($itemsElement->parentNode === $invoiceElement) {
                    $nodesToReorder['items'] = $itemsElement;
                    $invoiceElement->removeChild($itemsElement);
                }
                
                if ($taxesOutputsElement && $taxesOutputsElement->parentNode === $invoiceElement) {
                    $nodesToReorder['taxesOutputs'] = $taxesOutputsElement;
                    $invoiceElement->removeChild($taxesOutputsElement);
                }
                
                if ($invoiceTotalsElement->parentNode === $invoiceElement) {
                    $nodesToReorder['invoiceTotals'] = $invoiceTotalsElement;
                    $invoiceElement->removeChild($invoiceTotalsElement);
                }

                // Reinsertar en el orden correcto según el esquema:
                // InvoiceIssueData -> TaxesOutputs -> Items -> InvoiceTotals
                $referenceNode = $invoiceIssueDataElement;
                
                // 1. TaxesOutputs debe ir después de InvoiceIssueData (PRIMERO)
                if (isset($nodesToReorder['taxesOutputs'])) {
                    if ($referenceNode) {
                        // Buscar el siguiente elemento después de InvoiceIssueData
                        $nextNode = $referenceNode->nextSibling;
                        while ($nextNode && $nextNode->nodeType !== XML_ELEMENT_NODE) {
                            $nextNode = $nextNode->nextSibling;
                        }
                        if ($nextNode) {
                            $invoiceElement->insertBefore($nodesToReorder['taxesOutputs'], $nextNode);
                        } else {
                            $invoiceElement->appendChild($nodesToReorder['taxesOutputs']);
                        }
                    } else {
                        // Si no hay InvoiceIssueData, buscar InvoiceHeader
                        $invoiceHeaderElement = null;
                        foreach ($invoiceElement->childNodes as $child) {
                            if ($child->nodeType === XML_ELEMENT_NODE) {
                                $tagName = $child->localName ?? $child->nodeName;
                                if ($tagName === 'InvoiceHeader') {
                                    $invoiceHeaderElement = $child;
                                    break;
                                }
                            }
                        }
                        if ($invoiceHeaderElement) {
                            $nextNode = $invoiceHeaderElement->nextSibling;
                            while ($nextNode && $nextNode->nodeType !== XML_ELEMENT_NODE) {
                                $nextNode = $nextNode->nextSibling;
                            }
                            if ($nextNode) {
                                $invoiceElement->insertBefore($nodesToReorder['taxesOutputs'], $nextNode);
                            } else {
                                $invoiceElement->appendChild($nodesToReorder['taxesOutputs']);
                            }
                        } else {
                            $invoiceElement->appendChild($nodesToReorder['taxesOutputs']);
                        }
                    }
                    $referenceNode = $nodesToReorder['taxesOutputs'];
                }
                
                // 2. Items debe ir después de TaxesOutputs
                if (isset($nodesToReorder['items'])) {
                    if ($referenceNode) {
                        $nextNode = $referenceNode->nextSibling;
                        while ($nextNode && $nextNode->nodeType !== XML_ELEMENT_NODE) {
                            $nextNode = $nextNode->nextSibling;
                        }
                        if ($nextNode) {
                            $invoiceElement->insertBefore($nodesToReorder['items'], $nextNode);
                        } else {
                            $invoiceElement->appendChild($nodesToReorder['items']);
                        }
                    } else {
                        // Si no hay TaxesOutputs, insertar después de InvoiceIssueData
                        if ($invoiceIssueDataElement) {
                            $nextNode = $invoiceIssueDataElement->nextSibling;
                            while ($nextNode && $nextNode->nodeType !== XML_ELEMENT_NODE) {
                                $nextNode = $nextNode->nextSibling;
                            }
                            if ($nextNode) {
                                $invoiceElement->insertBefore($nodesToReorder['items'], $nextNode);
                            } else {
                                $invoiceElement->appendChild($nodesToReorder['items']);
                            }
                        } else {
                            $invoiceElement->appendChild($nodesToReorder['items']);
                        }
                    }
                    $referenceNode = $nodesToReorder['items'];
                }
                
                // 3. InvoiceTotals debe ir después de Items (o TaxesOutputs si no hay Items)
                if (isset($nodesToReorder['invoiceTotals'])) {
                    if ($referenceNode) {
                        $nextNode = $referenceNode->nextSibling;
                        while ($nextNode && $nextNode->nodeType !== XML_ELEMENT_NODE) {
                            $nextNode = $nextNode->nextSibling;
                        }
                        if ($nextNode) {
                            $invoiceElement->insertBefore($nodesToReorder['invoiceTotals'], $nextNode);
                        } else {
                            $invoiceElement->appendChild($nodesToReorder['invoiceTotals']);
                        }
                    } else {
                        $invoiceElement->appendChild($nodesToReorder['invoiceTotals']);
                    }
                }
            }

            // Verificar el orden final después del reordenamiento
            if ($necesitaReordenar) {
                $finalPositions = [];
                $position = 0;
                foreach ($invoiceElement->childNodes as $child) {
                    if ($child->nodeType === XML_ELEMENT_NODE) {
                        $tagName = $child->localName ?? $child->nodeName;
                        $finalPositions[$tagName] = $position;
                        $position++;
                    }
                }
                \Illuminate\Support\Facades\Log::info('FacturaeOrderFixExtension: Orden final después del reordenamiento', [
                    'posiciones' => $finalPositions
                ]);
            }
            
            // Obtener el XML corregido
            // Usar saveXML() sin argumentos para obtener todo el documento incluyendo la declaración
            // pero luego quitarla porque Facturae la agregará después
            $xmlCorregido = $dom->saveXML();
            
            // Eliminar la declaración XML si existe (Facturae la agregará después)
            if (strpos($xmlCorregido, '<?xml') === 0) {
                $xmlCorregido = substr($xmlCorregido, strpos($xmlCorregido, '>') + 1);
                $xmlCorregido = trim($xmlCorregido);
            }
            
            \Illuminate\Support\Facades\Log::info('FacturaeOrderFixExtension: XML corregido exitosamente');
            
            return $xmlCorregido;

        } catch (\Throwable $e) {
            // En caso de cualquier error, devolver el XML original
            // Esto es crítico: nunca debemos romper la generación de la factura
            \Illuminate\Support\Facades\Log::error('Error al corregir orden XML en extensión Facturae', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            // Devolver el XML original para que la factura se pueda generar igualmente
            return $xml;
        }
    }
}

