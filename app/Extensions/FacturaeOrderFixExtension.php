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
                return $xml;
            }
            
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
            
            // Verificar si necesitamos reordenar
            $necesitaReordenar = false;
            
            if ($itemsPosition !== -1 && $totalsPosition !== -1 && $totalsPosition < $itemsPosition) {
                $necesitaReordenar = true;
            }
            
            if ($taxesOutputsPosition !== -1 && $itemsPosition !== -1 && $taxesOutputsPosition < $itemsPosition) {
                $necesitaReordenar = true;
            }
            
            if ($taxesOutputsPosition !== -1 && $totalsPosition !== -1 && $taxesOutputsPosition > $totalsPosition) {
                $necesitaReordenar = true;
            }

            // También verificar si Items está después de TaxesOutputs o InvoiceTotals
            if ($itemsElement) {
                // Buscar InvoiceIssueData para insertar Items después de él
                $invoiceIssueDataPosition = -1;
                $invoiceIssueDataElement = null;
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
                
                // Si Items no está inmediatamente después de InvoiceIssueData, necesitamos moverlo
                if ($invoiceIssueDataElement && $itemsPosition !== -1) {
                    $expectedItemsPosition = $invoiceIssueDataPosition + 1;
                    if ($itemsPosition !== $expectedItemsPosition) {
                        $necesitaReordenar = true;
                    }
                }
            }

            // El orden correcto según Facturae 3.2.1 es:
            // InvoiceHeader -> InvoiceIssueData -> Items -> TaxesOutputs -> InvoiceTotals
            // La biblioteca genera: InvoiceHeader -> InvoiceIssueData -> TaxesOutputs -> InvoiceTotals -> Items
            
            if ($necesitaReordenar && $itemsElement && $invoiceTotalsElement) {
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

                // Reinsertar en el orden correcto
                // 1. Items debe ir después de InvoiceIssueData
                $referenceNode = $invoiceIssueDataElement;
                
                if (isset($nodesToReorder['items'])) {
                    if ($referenceNode) {
                        // Buscar el siguiente elemento después de InvoiceIssueData
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
                
                // 2. TaxesOutputs debe ir después de Items
                if (isset($nodesToReorder['taxesOutputs'])) {
                    if ($referenceNode) {
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
                        $invoiceElement->appendChild($nodesToReorder['taxesOutputs']);
                    }
                    $referenceNode = $nodesToReorder['taxesOutputs'];
                }
                
                // 3. InvoiceTotals debe ir después de TaxesOutputs (o Items si no hay TaxesOutputs)
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

            // Obtener el XML corregido
            // Usar saveXML() sin argumentos para obtener todo el documento incluyendo la declaración
            // pero luego quitarla porque Facturae la agregará después
            $xmlCorregido = $dom->saveXML();
            
            // Eliminar la declaración XML si existe (Facturae la agregará después)
            if (strpos($xmlCorregido, '<?xml') === 0) {
                $xmlCorregido = substr($xmlCorregido, strpos($xmlCorregido, '>') + 1);
                $xmlCorregido = trim($xmlCorregido);
            }
            
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

