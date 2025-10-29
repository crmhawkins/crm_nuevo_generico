<?php

namespace App\Extensions;

class FacturaeOrderFixExtension
{
    /**
     * Corrige el orden de los elementos XML antes de firmar
     * Orden requerido: Items -> TaxesOutputs -> InvoiceTotals
     */
    public function __onBeforeSign($xml)
    {
        try {
            // El XML recibido ya está completo pero sin declaración XML
            // Debe tener la estructura: <fe:Facturae>...</fe:Facturae>
            
            // Cargar el XML
            $dom = new \DOMDocument();
            $dom->preserveWhiteSpace = false;
            $dom->formatOutput = false;
            
            // El XML puede o no tener declaración XML, manejarlo
            $xmlClean = $xml;
            if (strpos($xmlClean, '<?xml') === 0) {
                $xmlClean = substr($xmlClean, strpos($xmlClean, '>') + 1);
                $xmlClean = trim($xmlClean);
            }
            
            // Asegurar que el XML está completo
            if (empty($xmlClean)) {
                return $xml;
            }
            
            $dom->loadXML($xmlClean);

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

            if ($necesitaReordenar && $itemsElement && $invoiceTotalsElement) {
                // Remover elementos desordenados
                $nodesToReorder = [];
                
                if ($taxesOutputsElement && $taxesOutputsElement->parentNode === $invoiceElement) {
                    $nodesToReorder['taxesOutputs'] = $taxesOutputsElement;
                    $invoiceElement->removeChild($taxesOutputsElement);
                }
                
                if ($invoiceTotalsElement->parentNode === $invoiceElement) {
                    $nodesToReorder['invoiceTotals'] = $invoiceTotalsElement;
                    $invoiceElement->removeChild($invoiceTotalsElement);
                }

                // Insertar en el orden correcto: Items -> TaxesOutputs -> InvoiceTotals
                $insertAfterItems = $itemsElement->nextSibling;
                
                // Insertar TaxesOutputs después de Items
                if (isset($nodesToReorder['taxesOutputs'])) {
                    if ($insertAfterItems) {
                        $invoiceElement->insertBefore($nodesToReorder['taxesOutputs'], $insertAfterItems);
                    } else {
                        $invoiceElement->appendChild($nodesToReorder['taxesOutputs']);
                    }
                    $insertAfterItems = $nodesToReorder['taxesOutputs']->nextSibling;
                }

                // Insertar InvoiceTotals después de TaxesOutputs (o después de Items si no hay TaxesOutputs)
                if (isset($nodesToReorder['invoiceTotals'])) {
                    if ($insertAfterItems) {
                        $invoiceElement->insertBefore($nodesToReorder['invoiceTotals'], $insertAfterItems);
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

        } catch (\Exception $e) {
            // En caso de error, devolver el XML original
            \Illuminate\Support\Facades\Log::error('Error al corregir orden XML en extensión Facturae', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return $xml;
        }
    }
}

