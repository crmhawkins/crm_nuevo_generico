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
            $invoiceIssueDataElement = null;

            foreach ($invoiceElement->childNodes as $child) {
                if ($child->nodeType === XML_ELEMENT_NODE) {
                    $tagName = $child->localName ?? $child->nodeName;
                    
                    if ($tagName === 'Items') {
                        $itemsElement = $child;
                    } elseif ($tagName === 'TaxesOutputs') {
                        $taxesOutputsElement = $child;
                    } elseif ($tagName === 'InvoiceTotals') {
                        $invoiceTotalsElement = $child;
                    } elseif ($tagName === 'InvoiceIssueData') {
                        $invoiceIssueDataElement = $child;
                    }
                }
            }

            // TaxesOutputs es OBLIGATORIO según el esquema, incluso si está vacío
            // Si no existe, debemos crearlo vacío ANTES de Items
            $taxesOutputsCreado = false;
            if (!$taxesOutputsElement && ($itemsElement || $invoiceTotalsElement)) {
                \Illuminate\Support\Facades\Log::info('FacturaeOrderFixExtension: Creando TaxesOutputs vacío (requerido por el esquema)');
                
                // Obtener el namespace del Invoice para crear el elemento con el mismo namespace
                $namespaceURI = $invoiceElement->namespaceURI ?? 'http://www.facturae.es/Facturae/2014/v3.2.1/Facturae';
                
                // Crear TaxesOutputs vacío usando el mismo namespace que Invoice
                $taxesOutputsElement = $dom->createElementNS($namespaceURI, 'TaxesOutputs');
                
                // No agregar contenido, está vacío
                $taxesOutputsCreado = true;
                \Illuminate\Support\Facades\Log::info('FacturaeOrderFixExtension: TaxesOutputs vacío creado correctamente');
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
            
            // Si hay TaxesOutputs, verificar el orden completo
            if ($invoiceIssueDataPosition !== -1 && $taxesOutputsPosition !== -1 && $itemsPosition !== -1 && $totalsPosition !== -1) {
                // Verificar: InvoiceIssueData < TaxesOutputs < Items < InvoiceTotals
                if ($invoiceIssueDataPosition < $taxesOutputsPosition && 
                    $taxesOutputsPosition < $itemsPosition && 
                    $itemsPosition < $totalsPosition) {
                    $ordenCorrecto = true;
                }
            } elseif ($invoiceIssueDataPosition !== -1 && !$taxesOutputsElement && $itemsPosition !== -1 && $totalsPosition !== -1) {
                // Si no hay TaxesOutputs, verificar que Items esté después de InvoiceIssueData y antes de InvoiceTotals
                if ($invoiceIssueDataPosition < $itemsPosition && $itemsPosition < $totalsPosition) {
                    $ordenCorrecto = true;
                }
            }
            
            if ($ordenCorrecto) {
                $necesitaReordenar = false;
                \Illuminate\Support\Facades\Log::info('FacturaeOrderFixExtension: El orden ya es correcto, no se requiere reordenamiento');
            } else {
                \Illuminate\Support\Facades\Log::info('FacturaeOrderFixExtension: El orden es incorrecto, se requiere reordenamiento', [
                    'invoiceIssueData_pos' => $invoiceIssueDataPosition,
                    'taxesOutputs_pos' => $taxesOutputsPosition,
                    'items_pos' => $itemsPosition,
                    'totals_pos' => $totalsPosition
                ]);
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
            
            // Necesitamos reordenar si tenemos los elementos necesarios
            $tieneElementosNecesarios = $itemsElement && $invoiceTotalsElement;
            
            // Si TaxesOutputs existe, siempre debe estar antes de Items según el esquema
            // Si no existe TaxesOutputs, entonces Items debe ir después de InvoiceIssueData
            if ($taxesOutputsElement && $taxesOutputsPosition !== -1 && $itemsPosition !== -1) {
                if ($taxesOutputsPosition > $itemsPosition) {
                    $necesitaReordenar = true;
                }
            }
            
            if ($necesitaReordenar && $tieneElementosNecesarios) {
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
                
                // TaxesOutputs: remover si ya existe, o usar el creado si es nuevo
                if ($taxesOutputsElement) {
                    if ($taxesOutputsElement->parentNode === $invoiceElement) {
                        // Si ya existía y tiene parent, removerlo
                        $nodesToReorder['taxesOutputs'] = $taxesOutputsElement;
                        $invoiceElement->removeChild($taxesOutputsElement);
                    } else {
                        // Si es nuevo (creado por nosotros), agregarlo al array sin removerlo
                        $nodesToReorder['taxesOutputs'] = $taxesOutputsElement;
                    }
                }
                
                if ($invoiceTotalsElement->parentNode === $invoiceElement) {
                    $nodesToReorder['invoiceTotals'] = $invoiceTotalsElement;
                    $invoiceElement->removeChild($invoiceTotalsElement);
                }

                // Reinsertar en el orden correcto según el esquema:
                // InvoiceIssueData -> TaxesOutputs -> Items -> InvoiceTotals
                
                // Función auxiliar para encontrar el siguiente elemento después de un nodo
                $findNextElement = function($node) {
                    $next = $node->nextSibling;
                    while ($next && $next->nodeType !== XML_ELEMENT_NODE) {
                        $next = $next->nextSibling;
                    }
                    return $next;
                };
                
                // Encontrar el punto de inserción (después de InvoiceIssueData)
                $insertionPoint = $invoiceIssueDataElement;
                $nextAfterInsertion = $insertionPoint ? $findNextElement($insertionPoint) : null;
                
                \Illuminate\Support\Facades\Log::info('FacturaeOrderFixExtension: Punto de inserción', [
                    'insertion_point' => $insertionPoint ? ($insertionPoint->localName ?? $insertionPoint->nodeName) : 'null',
                    'next_after_insertion' => $nextAfterInsertion ? ($nextAfterInsertion->localName ?? $nextAfterInsertion->nodeName) : 'null',
                    'tiene_taxesOutputs' => isset($nodesToReorder['taxesOutputs']),
                    'tiene_items' => isset($nodesToReorder['items']),
                    'tiene_invoiceTotals' => isset($nodesToReorder['invoiceTotals'])
                ]);
                
                // Si no hay punto de inserción, usar el último hijo
                if (!$insertionPoint) {
                    // Buscar el último elemento hijo de Invoice
                    $lastChild = null;
                    foreach ($invoiceElement->childNodes as $child) {
                        if ($child->nodeType === XML_ELEMENT_NODE) {
                            $lastChild = $child;
                        }
                    }
                    $insertionPoint = $lastChild;
                    $nextAfterInsertion = null;
                }
                
                // 1. Insertar TaxesOutputs después de InvoiceIssueData (PRIMERO, si existe)
                if (isset($nodesToReorder['taxesOutputs']) && $insertionPoint) {
                    if ($nextAfterInsertion) {
                        $invoiceElement->insertBefore($nodesToReorder['taxesOutputs'], $nextAfterInsertion);
                    } else {
                        $invoiceElement->appendChild($nodesToReorder['taxesOutputs']);
                    }
                    // Actualizar el punto de inserción para el siguiente elemento
                    $insertionPoint = $nodesToReorder['taxesOutputs'];
                    $nextAfterInsertion = $findNextElement($insertionPoint);
                    \Illuminate\Support\Facades\Log::info('FacturaeOrderFixExtension: TaxesOutputs insertado después de InvoiceIssueData');
                }
                
                // 2. Insertar Items después de TaxesOutputs (o después de InvoiceIssueData si no hay TaxesOutputs)
                if (isset($nodesToReorder['items']) && $insertionPoint) {
                    if ($nextAfterInsertion) {
                        $invoiceElement->insertBefore($nodesToReorder['items'], $nextAfterInsertion);
                    } else {
                        $invoiceElement->appendChild($nodesToReorder['items']);
                    }
                    // Actualizar el punto de inserción para el siguiente elemento
                    $insertionPoint = $nodesToReorder['items'];
                    $nextAfterInsertion = $findNextElement($insertionPoint);
                    \Illuminate\Support\Facades\Log::info('FacturaeOrderFixExtension: Items insertado después de TaxesOutputs');
                } elseif (isset($nodesToReorder['items']) && $invoiceIssueDataElement) {
                    // Si no hay TaxesOutputs, insertar Items directamente después de InvoiceIssueData
                    $nextAfterIssueData = $findNextElement($invoiceIssueDataElement);
                    if ($nextAfterIssueData) {
                        $invoiceElement->insertBefore($nodesToReorder['items'], $nextAfterIssueData);
                    } else {
                        $invoiceElement->appendChild($nodesToReorder['items']);
                    }
                    $insertionPoint = $nodesToReorder['items'];
                    $nextAfterInsertion = $findNextElement($insertionPoint);
                    \Illuminate\Support\Facades\Log::info('FacturaeOrderFixExtension: Items insertado directamente después de InvoiceIssueData (sin TaxesOutputs)');
                }
                
                // 3. Insertar InvoiceTotals después de Items (último)
                if (isset($nodesToReorder['invoiceTotals']) && $insertionPoint) {
                    if ($nextAfterInsertion) {
                        $invoiceElement->insertBefore($nodesToReorder['invoiceTotals'], $nextAfterInsertion);
                    } else {
                        $invoiceElement->appendChild($nodesToReorder['invoiceTotals']);
                    }
                    \Illuminate\Support\Facades\Log::info('FacturaeOrderFixExtension: InvoiceTotals insertado después de Items');
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
            
            // Guardar una muestra del XML para debugging (solo los primeros elementos)
            $xmlPreview = substr($xmlCorregido, 0, 1000);
            \Illuminate\Support\Facades\Log::info('FacturaeOrderFixExtension: XML corregido exitosamente', [
                'xml_preview' => $xmlPreview,
                'xml_length' => strlen($xmlCorregido)
            ]);
            
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

