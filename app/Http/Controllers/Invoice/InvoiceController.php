<?php

namespace App\Http\Controllers\Invoice;

use App\Http\Controllers\Controller;
use App\Mail\MailInvoice;
use App\Models\Budgets\Budget;
use App\Models\Budgets\BudgetConceptType;
use App\Models\Budgets\InvoiceCustomPDF;
use App\Models\Clients\Client;
use App\Models\Company\CompanyDetails;
use App\Models\Invoices\Invoice;
use App\Models\Invoices\InvoiceConcepts;
use App\Models\Invoices\InvoiceStatus;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use josemmo\Facturae\Facturae;
use josemmo\Facturae\FacturaeItem;
use josemmo\Facturae\FacturaeParty;
use ZipArchive;

class InvoiceController extends Controller
{
    public function index()
    {
        $facturas = Invoice::all();
        return view('invoices.index', compact('facturas'));
    }
    public function edit(string $id)
    {
        $factura = Invoice::where('id', $id)->get()->first();
        $invoiceStatuses = InvoiceStatus::all();
        $invoice_concepts = InvoiceConcepts::where('invoice_id', $factura->id)->get();

        return view('invoices.edit', compact( 'factura', 'invoiceStatuses', 'invoice_concepts'));
    }

    public function cobrarFactura(Request $request)
    {
        $id = $request->id;
        $invoice = Invoice::find($id);

        $invoice->invoice_status_id = 3;
        $invoice->save();
        return response(200);
        // session()->flash('toast', [
        //     'icon' => 'success',
        //     'mensaje' => 'El presupuesto cambio su estado a Aceptado'
        // ]);
        // return redirect(route('presupuesto.edit', $id));
    }

    public function update(Request $request, string $id)
    {
        $factura = Invoice::find($id);
        // Validación

        $data = $request->validate([
            'invoice_status_id' => 'required',
            'observations' => 'nullable',
            'note' => 'nullable',
            'show_summary' => 'nullable',
            'creation_date' => 'nullable',
            'paid_date' => 'nullable',
            'expiration_date' => 'nullable',
            'iva_percentage' => 'nullable|numeric|min:0|max:100',
            'retencion_percentage' => 'nullable|numeric|min:0|max:100',
            'iva' => 'nullable|numeric|min:0',
            'retencion' => 'nullable|numeric|min:0',
            'base' => 'nullable|numeric|min:0',
            'gross' => 'nullable|numeric|min:0',
            'total' => 'nullable|numeric',
        ]);

        // Formulario datos

        $facturaupdated=$factura->update($data);

        if($facturaupdated){
            return redirect()->route('facturas.index')->with('toast', [
                'icon' => 'success',
                'mensaje' => 'Presupuesto actualizado correctamente.'
            ]);
        }else{
            return redirect()->back()->with('toast', [
                'icon' => 'error',
                'mensaje' => 'Error al actualizar el presupuesto.'
            ]);
        }
    }

    public function generatePDF(Request $request)
    {
        // Buscar la factura por ID
        $invoice = Invoice::find($request->id);

        // Validar que la factura exista
        if (!$invoice) {
            return response()->json(['error' => 'Factura no encontrada'], 404);
        }

        $pdf =  $this->createPdf($invoice);
        // Descargar el PDF con el nombre 'factura_XYZ_fecha.pdf'
        return $pdf->download('factura_' . $invoice->reference . '_' . Carbon::now()->format('Y-m-d') . '.pdf');
    }

    public function createPdf(invoice $invoice){

        // Obtener los conceptos de esta factura
        $thisInvoiceConcepts = InvoiceConcepts::where('invoice_id', $invoice->id)->get();
        // Título del PDF
        $title = "Factura - " . $invoice->reference;
        // Datos básicos para pasar a la vista del PDF
        $data = [
            'title' => $title,
            'invoice_reference' => $invoice->reference,
        ];
        // Formatear los conceptos para usarlos en la vista
        $invoiceConceptsFormated = [];

        foreach ($thisInvoiceConcepts as $invoiceConcept) {
            // Validar que tenga unidades mayores a 0 para evitar división por 0
            if ($invoiceConcept->units > 0) {
                // Título
                $invoiceConceptsFormated[$invoiceConcept->id]['title'] = $invoiceConcept->title ?? 'Título no disponible';
                // Unidades
                $invoiceConceptsFormated[$invoiceConcept->id]['units'] = $invoiceConcept->units;

                // Precio por unidad
                $invoiceConceptsFormated[$invoiceConcept->id]['price_unit'] = round($invoiceConcept->total / $invoiceConcept->units, 2);

                // Calcular subtotal y precio en función del tipo de concepto
                if ($invoiceConcept->concept_type_id == BudgetConceptType::TYPE_OWN) {
                    $invoiceConceptsFormated[$invoiceConcept->id]['subtotal'] = number_format((float)$invoiceConcept->units * $invoiceConcept->sale_price, 2, '.', '');
                    $invoiceConceptsFormated[$invoiceConcept->id]['price_unit'] = number_format((float)$invoiceConcept->sale_price, 2, '.', '');
                } elseif ($invoiceConcept->concept_type_id == BudgetConceptType::TYPE_SUPPLIER) {
                    $purchasePriceWithoutMarginBenefit = $invoiceConcept->purchase_price;
                    $benefitMargin = $invoiceConcept->benefit_margin;
                    $marginBenefitToAdd  = ($purchasePriceWithoutMarginBenefit * $benefitMargin) / 100;
                    $purchasePriceWithMarginBenefit  = $purchasePriceWithoutMarginBenefit + $marginBenefitToAdd;
                    $invoiceConceptsFormated[$invoiceConcept->id]['price_unit'] = round($purchasePriceWithMarginBenefit / $invoiceConcept->units, 2);
                    $invoiceConceptsFormated[$invoiceConcept->id]['subtotal'] = number_format((float)$invoiceConcept->total_no_discount, 2, '.', '');
                }
                // Descuento
                $invoiceConceptsFormated[$invoiceConcept->id]['discount'] = number_format((float)($invoiceConcept->discount ?? 0), 2, ',', '');
                // Total
                $invoiceConceptsFormated[$invoiceConcept->id]['total'] = number_format((float)$invoiceConcept->total, 2, ',', '');
                // Formatear la descripción dividiendo en líneas
                $rawConcepts = $invoiceConcept->concept ?? '';
                $arrayConceptStringsAndBreakLines = explode(PHP_EOL, $rawConcepts);

                $maxLineLength = 50;
                $charactersInALineCounter = 0;
                $arrayWordsFormated = [];
                $counter = 0;
                $firstWordTempRow = true;

                foreach ($arrayConceptStringsAndBreakLines as $stringItem) {
                    $rowWords = explode(' ', $stringItem);
                    $tempRow = '';

                    foreach ($rowWords as $word) {
                        $wordLength = strlen($word);

                        if (!$firstWordTempRow && ($charactersInALineCounter + $wordLength) > $maxLineLength) {
                            // Guardar la fila actual y reiniciar el contador
                            $arrayWordsFormated[$counter] = trim($tempRow);
                            $counter++;
                            $tempRow = $word;
                            $charactersInALineCounter = $wordLength;
                        } else {
                            $tempRow .= ($firstWordTempRow ? '' : ' ') . $word;
                            $charactersInALineCounter += $wordLength;
                            $firstWordTempRow = false;
                        }
                    }

                    // Guardar la última fila
                    $arrayWordsFormated[$counter] = trim($tempRow);
                    $counter++;
                    $charactersInALineCounter = 0;
                    $firstWordTempRow = true;
                }

                $invoiceConceptsFormated[$invoiceConcept->id]['description'] = $arrayWordsFormated;
            } else {

                // Manejar casos donde las unidades sean 0 o nulas
                $invoiceConceptsFormated[$invoiceConcept->id] = [
                    'title' => $invoiceConcept->title ?? 'Título no disponible',
                    'units' => 0,
                    'price_unit' => 0,
                    'subtotal' => 0,
                    'discount' => '0,00',
                    'total' => '0,00',
                    'description' => ['Descripción no disponible']
                ];
            }
        }
        $empresa = CompanyDetails::get()->first();
        // Generar el PDF usando la vista 'invoices.previewPDF'
        $pdf = PDF::loadView('invoices.previewPDF', compact('empresa','invoice','data', 'invoiceConceptsFormated'));
        return $pdf;
    }

    public function generateMultiplePDFs(Request $request)
    {
        // Obtener las facturas por sus IDs
        $invoices = Invoice::whereIn('id', $request->invoice_ids)->get();

        // Verificar que se encontraron facturas
        if ($invoices->isEmpty()) {
            return response()->json(['error' => 'No se encontraron facturas'], 404);
        }

        // Crear una carpeta temporal para almacenar los archivos PDF
        $tempDirectory = storage_path('app/public/temp/invoices/');
        if (!file_exists($tempDirectory)) {
            mkdir($tempDirectory, 0755, true);
        }

        // Almacenar los nombres de los archivos PDF generados
        $pdfFiles = [];

        foreach ($invoices as $invoice) {

            $pdf = $this->createPDF($invoice);

            // Guardar el archivo PDF en la carpeta temporal
            $pdfFilePath = $tempDirectory . 'factura_' . $invoice->reference . '_' . Carbon::now()->format('Y-m-d') . '.pdf';
            $pdf->save($pdfFilePath);

            // Añadir el archivo generado al array
            $pdfFiles[] = $pdfFilePath;
        }

        // Crear un archivo ZIP que contendrá todos los PDFs
        $zipFileName = 'facturas_' . Carbon::now()->format('Y-m-d') . '.zip';
        $zipFilePath = storage_path('app/public/temp/' . $zipFileName);

        $zip = new ZipArchive();
        if ($zip->open($zipFilePath, ZipArchive::CREATE) === TRUE) {
            // Agregar cada archivo PDF al ZIP
            foreach ($pdfFiles as $file) {
                $zip->addFile($file, basename($file));
            }
            $zip->close();
        }

        // Eliminar los archivos PDF individuales después de crear el ZIP
        foreach ($pdfFiles as $file) {
            unlink($file);
        }

        // Descargar el archivo ZIP
        return response()->download($zipFilePath)->deleteFileAfterSend(true);
    }



    public function rectificateInvoice(Request $request){
        $invoice = Invoice::find($request->id);
        // Si es rectificativa que de error
        if($invoice->rectification){
            return response()->json([
                'status' => false,
                'mensaje' => "La factura ya es rectificativa"
            ]);
        }
        $arrayUpdated = ['budget_status_id' => 4];
        $budget = Budget::where('id', $invoice->budget_id)->get()->first();
        $budget->budget_status_id = 4;
        $budget->save();
        $rectificationSuccess = Invoice::where('id', $invoice->id )->update(array(
            'invoice_status_id' =>  5, //cancelada
            'rectification' =>  1,
        ));


        // Actualizar a rectificada
        $rectificationSuccess = Invoice::where('id', $invoice->id )->get()->first();
        $new_factura = $rectificationSuccess->replicate();
        $new_factura->total = -$new_factura->total;
        $new_factura->gross = -$new_factura->gross;
        $new_factura->base = -$new_factura->base;
        $new_factura->reference = 'N' . $invoice->reference;
        $new_factura->update(array(
            'invoice_status_id' =>  5, //cancelada
            'rectification' =>  1,
        ));
        $new_factura->push();

        $conceptos = InvoiceConcepts::where('invoice_id', $invoice->id)->get();

        foreach ($conceptos as $concept) {
            $new_concept = $concept->replicate();
            $new_concept->invoice_id = $new_factura->id;
            $new_concept->total = -$new_concept->total;
            $new_concept->push();
        }

        // Actualizar presupuesto a cancelado tras rectificar


        // Respuesta
        if($new_factura){
            return response()->json([
                'status' => true,
                'mensaje' => "Factura marcada como rectificativa.",
                'id' => $new_factura->id

            ]);

        }else{
            return response()->json([
                'status' => false,
                'mensaje' => "Error al actualizar datos."
            ]);
        }

    }

    public function destroy(Request $request){
        $id = $request->id;
        if ($id != null) {
            $invoice = Invoice::find($id);
            if ($invoice != null) {
                // Eliminar el presupuesto
                $invoice->delete();
                return response()->json([
                    'status' => true,
                    'mensaje' => "El factura fue borrado con éxito."
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'mensaje' => "Error 500 no se encuentra la factura."
                ]);
            }
        } else {
            return response()->json([
                'status' => false,
                'mensaje' => "Error 500 no se encuentra el ID en la petición."
            ]);
        }
    }

    public function sendInvoicePDF(Request $request)
    {
        $invoice = Invoice::where('id', $request->id)->get()->first();

        $filename = $this->savePDF($invoice);

        $data = [
            'file_name' => $filename
        ];

        $mailInvoice = new \stdClass();
        $mailInvoice->gestor = $invoice->adminUser->name." ".$invoice->adminUser->surname;
        $mailInvoice->gestorMail = $invoice->adminUser->email;
        $mailInvoice->gestorTel = '956 662 942';
        $mailInvoice->paymentMethodId = $invoice->paymentMethod->id;

        $email = new MailInvoice($mailInvoice, $filename);
        $empresa = CompanyDetails::get()->first();
        $mail = $empresa->email;
        Mail::to($request->email)
        ->cc( $mail)
        ->send($email);

        // Respuesta
        if(File::delete($filename)){
            // Respuesta
            return 200;
        }else{
            return 404;
        }

    }

    public function savePDF(Invoice $invoice){


        $name = 'factura_' . $invoice['reference'];
        $pathToSaveInvoice =  storage_path('app/public/assets/temp/' . $name . '.pdf');
        $directory = storage_path('app/public/assets/temp');
        if (!file_exists($directory)) {
            mkdir($directory, 0755, true); // Crear el directorio con permisos 0755 y true para crear subdirectorios si es necesario
        }
        $pdf = $this->createPdf($invoice);
        $pdf->save( $pathToSaveInvoice );
        return $pathToSaveInvoice;

    }

    public function electronica(Request $request)
    {
        try {
            // Validar que existe el ID
            if (!$request->id) {
                return response()->json([
                    'error' => 'No se proporcionó el ID de la factura.',
                    'status' => false
                ], 400);
            }

            $factura = Invoice::find($request->id);
            if (!$factura) {
                return response()->json([
                    'error' => 'La factura no existe o no se encontró.',
                    'status' => false
                ], 404);
            }

            $empresa = CompanyDetails::first();
            if (!$empresa) {
                return response()->json([
                    'error' => 'No se encontró la configuración de la empresa. Por favor, complete la configuración en el panel de administración.',
                    'status' => false
                ], 404);
            }

            $cliente = Client::where('id', $factura->client_id)->first();
            if (!$cliente) {
                return response()->json([
                    'error' => 'No se encontró el cliente asociado a esta factura.',
                    'status' => false
                ], 404);
            }

            $conceptos = InvoiceConcepts::where('invoice_id', $factura->id)->get();
            if ($conceptos->isEmpty()) {
                return response()->json([
                    'error' => 'La factura no tiene conceptos asociados. Por favor, agregue al menos un concepto antes de generar la factura electrónica.',
                    'status' => false
                ], 400);
            }

                // Crear instancia de Facturae con versión 3.2.1 del esquema (compatible con validación FACE)
                $fac = new Facturae(Facturae::SCHEMA_3_2_1);

                // Establecer la política oficial de firma (XAdES-EPES) para Facturae 3.2.1
                // Evita el error "La política de firma no es correcta" en FACE
                if (method_exists($fac, 'setSignaturePolicy')) {
                    $policy = [
                        'name' => 'Facturae 3.2.1 Signature Policy',
                        'url' => 'https://www.facturae.gob.es/formato/Politica_de_firma_formato_Facturae.pdf',
                        'digest' => [
                            'value' => '14EF5D2C33B15D6DD18D8F3F3C4B9B6C1B7D0FBA', // SHA-1 de la política
                            'method' => 'sha1'
                        ]
                    ];
                    \call_user_func([$fac, 'setSignaturePolicy'], $policy);
                }
            
            // Agregar extensión para corregir el orden de elementos antes de firmar usando reflexión
            try {
                $reflection = new \ReflectionClass($fac);
                $extensionsProperty = $reflection->getProperty('extensions');
                $extensionsProperty->setAccessible(true);
                $extensions = $extensionsProperty->getValue($fac);
                
                if (!is_array($extensions)) {
                    $extensions = [];
                }
                
                $extension = new \App\Extensions\FacturaeOrderFixExtension();
                $extensions[] = $extension;
                $extensionsProperty->setValue($fac, $extensions);
                
                Log::info('Extensión FacturaeOrderFixExtension agregada correctamente', [
                    'total_extensions' => count($extensions)
                ]);
            } catch (\Exception $e) {
                Log::error('Error al agregar extensión FacturaeOrderFixExtension', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
                // Continuar sin la extensión, el método de respaldo corregirá el orden
            }

            // Validar que la referencia tiene el formato correcto
            if (empty($factura->reference) || strpos($factura->reference, '-') === false) {
                return response()->json([
                    'error' => 'La referencia de la factura no tiene el formato correcto (debe ser: número-serie).',
                    'status' => false
                ], 400);
            }

            $partes = explode('-', $factura->reference);
            if (count($partes) < 2) {
                return response()->json([
                    'error' => 'La referencia de la factura no tiene el formato correcto. Debe contener al menos un guion (-).',
                    'status' => false
                ], 400);
            }

            $numero = $partes[0];
            $serie = $partes[1];
            
            if (empty($numero) || empty($serie)) {
                return response()->json([
                    'error' => 'La referencia de la factura está incompleta. Falta el número o la serie.',
                    'status' => false
                ], 400);
            }

            $fac->setNumber($numero, $serie);

            // Validar y asignar fechas
            if (empty($factura->created_at)) {
                return response()->json([
                    'error' => 'La factura no tiene fecha de creación. Por favor, verifique la factura.',
                    'status' => false
                ], 400);
            }

            // Validar y procesar fechas
            try {
                // Intentar parsear fecha de creación
                $fecha = Carbon::parse($factura->created_at)->format('Y-m-d');
                
                // Validar y parsear fecha de vencimiento
                $expirationDate = $factura->expiration_date;
                
                // Verificar si la fecha existe antes de intentar parsearla
                if (empty($expirationDate) || 
                    $expirationDate === null ||
                    (is_string($expirationDate) && trim($expirationDate) === '') ||
                    $expirationDate === '0000-00-00' ||
                    $expirationDate === '0000-00-00 00:00:00') {
                    
                    // Si la fecha no existe en el modelo, recargar desde la BD
                    $factura->refresh();
                    $expirationDate = $factura->expiration_date;
                    
                    // Si después de recargar sigue vacía, entonces no existe
                    if (empty($expirationDate) || 
                        $expirationDate === null ||
                        (is_string($expirationDate) && trim($expirationDate) === '') ||
                        $expirationDate === '0000-00-00' ||
                        $expirationDate === '0000-00-00 00:00:00') {
                        return response()->json([
                            'error' => 'La factura no tiene fecha de vencimiento configurada. Por favor, agregue una fecha de vencimiento en el formulario de edición.',
                            'status' => false
                        ], 400);
                    }
                }
                
                // Intentar parsear la fecha de vencimiento
                try {
                    $fechafinal = Carbon::parse($expirationDate)->format('Y-m-d');
                    
                    // Validar que la fecha parseada sea válida (no 1970-01-01 u otra fecha por defecto)
                    if ($fechafinal === '1970-01-01' || $fechafinal === '0000-00-00') {
                        throw new \Exception('Fecha de vencimiento inválida');
                    }
                } catch (\Exception $parseError) {
                    Log::error('Error al parsear fecha de vencimiento', [
                        'factura_id' => $factura->id,
                        'expiration_date' => $expirationDate,
                        'error' => $parseError->getMessage()
                    ]);
                    
                    return response()->json([
                        'error' => 'La fecha de vencimiento no tiene un formato válido. Por favor, verifique que la fecha esté correctamente configurada.',
                        'status' => false
                    ], 400);
                }
                
            } catch (\Exception $e) {
                Log::error('Error al procesar fechas de la factura', [
                    'factura_id' => $factura->id ?? null,
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
                
                return response()->json([
                    'error' => 'Error al procesar las fechas de la factura: ' . $e->getMessage(),
                    'status' => false
                ], 400);
            }

            $fac->setIssueDate($fecha);
            $fac->setBillingPeriod($fecha, $fechafinal);

            // Validar datos de la empresa
            if (empty($empresa->nif)) {
                return response()->json([
                    'error' => 'El NIF de la empresa no está configurado. Por favor, complete la configuración de la empresa.',
                    'status' => false
                ], 400);
            }

            if (empty($empresa->company_name)) {
                return response()->json([
                    'error' => 'El nombre de la empresa no está configurado. Por favor, complete la configuración de la empresa.',
                    'status' => false
                ], 400);
            }

            // Incluimos los datos del vendedor
            $fac->setSeller(new FacturaeParty([
                "taxNumber" => $empresa->nif,
                "name"      => $empresa->company_name,
                "address"   => $empresa->address ?? '',
                "postCode"  => $empresa->postCode ?? '',
                "town"      => $empresa->town ?? '',
                "province"  => $empresa->province ?? ''
            ]));

            // Validar campos del cliente según tipo
            if ($cliente->tipoCliente == 1) {
                $camposRequeridos = [
                    'CIF' => $cliente->cif,
                    'Nombre' => $cliente->name,
                    'Primer Apellido' => $cliente->primerApellido,
                    'Segundo Apellido' => $cliente->segundoApellido,
                    'Dirección' => $cliente->address,
                    'Código Postal' => $cliente->zipcode,
                    'Ciudad' => $cliente->city,
                    'Provincia' => $cliente->province
                ];
            } else {
                $camposRequeridos = [
                    'CIF' => $cliente->cif,
                    'Nombre de la Empresa' => $cliente->company,
                    'Dirección' => $cliente->address,
                    'Código Postal' => $cliente->zipcode,
                    'Ciudad' => $cliente->city,
                    'Provincia' => $cliente->province
                ];
            }

            // Verificar si hay algún campo vacío
            $camposFaltantes = [];
            foreach ($camposRequeridos as $campo => $valor) {
                if (empty($valor)) {
                    $camposFaltantes[] = $campo;
                }
            }
            if (!empty($camposFaltantes)) {
                $mensaje = "Por favor, complete los siguientes campos del cliente en su ficha: " . implode(", ", $camposFaltantes);
                return response()->json([
                    'error' => $mensaje,
                    'status' => false
                ], 400);
            }

            // Configurar comprador según tipo
            if($cliente->tipoCliente == 1){
                $fac->setBuyer(new FacturaeParty([
                    "isLegalEntity" => false,
                    "taxNumber"     => $cliente->cif,
                    "name"          => $cliente->name,
                    "firstSurname"  => $cliente->primerApellido,
                    "lastSurname"   => $cliente->segundoApellido,
                    "address"       => $cliente->address,
                    "postCode"      => $cliente->zipcode,
                    "town"          => $cliente->city,
                    "province"      => $cliente->province
                ]));
            } else {
                $fac->setBuyer(new FacturaeParty([
                    "isLegalEntity" => true,
                    "taxNumber"     => $cliente->cif,
                    "name"          => $cliente->company,
                    "address"       => $cliente->address,
                    "postCode"      => $cliente->zipcode,
                    "town"          => $cliente->city,
                    "province"      => $cliente->province,
                ]));
            }

            // Procesar conceptos y calcular totales
            $retencion = floatval($factura->retencion_percentage ?? 0);
            $iva = floatval($factura->iva_percentage ?? 0);
            
            // Calcular totales antes de agregar items para asegurar orden correcto
            $totalBaseImponible = 0;
            $totalIva = 0;
            $totalRetencion = 0;
            $totalFactura = 0;

            foreach ($conceptos as $concepto) {
                // Evitar divisiones por cero
                $unidad = max(1, $concepto->units);
                
                // Calcular precios
                $precioUnitario = $concepto->total_no_discount / $unidad;
                $subtotal = $precioUnitario * $concepto->units;
                $descuento = $concepto->discount ?? 0;
                $baseImponible = $subtotal - $descuento;
                
                // Calcular impuestos
                $ivaItem = ($baseImponible * $iva) / 100;
                $retencionItem = ($baseImponible * $retencion) / 100;
                
                // Acumular totales
                $totalBaseImponible += $baseImponible;
                $totalIva += $ivaItem;
                $totalRetencion += $retencionItem;
                
                $item = [
                    "articleCode" => $concepto->services_category_id ?? '',
                    "name" => $concepto->title ?? 'Concepto sin título',
                    "unitPriceWithoutTax" => $precioUnitario,
                    "quantity" => $concepto->units,
                    "taxes" => []
                ];

                // Añadir IVA si aplica
                if ($iva > 0) {
                    $item["taxes"][Facturae::TAX_IVA] = $iva;
                }

                // Añadir retención IRPF si aplica  
                if ($retencion > 0) {
                    $item["taxes"][Facturae::TAX_IRPF] = $retencion;
                }

                // Añadir descuento si existe
                if ($descuento > 0) {
                    $item["discounts"] = [
                        ["reason" => "Descuento", "amount" => $descuento]
                    ];
                }

                $fac->addItem(new FacturaeItem($item));
            }
            
            // Calcular total final
            $totalFactura = $totalBaseImponible + $totalIva - $totalRetencion;
            
            // Verificar que todos los items se hayan añadido correctamente
            // La biblioteca Facturae calculará automáticamente los TaxesOutputs 
            // cuando se exporte la factura, y debe hacerlo en el orden correcto
            // según el esquema: InvoiceLines -> TaxesOutputs -> InvoiceTotals
            
            // Asegurar que la versión del esquema es correcta
            if ($fac->getSchemaVersion() !== Facturae::SCHEMA_3_2_1) {
                $fac->setSchemaVersion(Facturae::SCHEMA_3_2_1);
            }

            // Validar certificado y contraseña
            $certificado = $empresa->certificado;
            $contrasena = $empresa->contrasena;

            if (empty($certificado)) {
                return response()->json([
                    'error' => 'Falta el certificado. Por favor, suba el certificado en la configuración de la empresa.',
                    'status' => false
                ], 400);
            }

            if (empty($contrasena)) {
                return response()->json([
                    'error' => 'Falta la contraseña del certificado. Por favor, configure la contraseña en la configuración de la empresa.',
                    'status' => false
                ], 400);
            }

            // Verificar que el archivo del certificado existe
            $certificadoPath = storage_path('app/public/' . $certificado);
            if (!file_exists($certificadoPath)) {
                return response()->json([
                    'error' => 'El archivo del certificado no se encuentra en el servidor. Por favor, vuelva a subir el certificado.',
                    'status' => false
                ], 404);
            }

            // Leer el certificado
            try {
                $encryptedStore = file_get_contents($certificadoPath);
                if ($encryptedStore === false) {
                    return response()->json([
                        'error' => 'No se pudo leer el archivo del certificado. Verifique los permisos del archivo.',
                        'status' => false
                    ], 500);
                }
            } catch (\Exception $e) {
                return response()->json([
                    'error' => 'Error al leer el certificado: ' . $e->getMessage(),
                    'status' => false
                ], 500);
            }

            // Firmar la factura
            try {
                $fac->sign($encryptedStore, null, $contrasena);
            } catch (\Exception $e) {
                return response()->json([
                    'error' => 'Error al firmar la factura electrónica: ' . $e->getMessage() . '. Verifique que el certificado y la contraseña sean correctos.',
                    'status' => false
                ], 500);
            }

            // Exportar la factura (la extensión corregirá el orden antes de firmar)
            try {
                $fac->export($numero.'-'.$serie.".xsig");
                
                $filePath = public_path($numero.'-'.$serie.".xsig");
                
                // Verificar que el archivo se generó
                if (!file_exists($filePath)) {
                    return response()->json([
                        'error' => 'El archivo de la factura electrónica no se generó correctamente. Verifique los permisos de escritura en el directorio público.',
                        'status' => false
                    ], 500);
                }
                
                // NOTA: No podemos corregir el XML después de firmar porque invalida la firma
                // La corrección debe hacerse ANTES de firmar usando la extensión __onBeforeSign
                
            } catch (\Exception $e) {
                return response()->json([
                    'error' => 'Error al exportar la factura electrónica: ' . $e->getMessage(),
                    'status' => false
                ], 500);
            }

            $filePath = public_path($numero.'-'.$serie.".xsig");

            return response()->download($filePath, "$numero-$serie.xsig", [
                'Content-Type' => 'application/xsig',
                'Content-Disposition' => 'attachment; filename="' . $numero . '-' . $serie . '.xsig"',
            ])->deleteFileAfterSend(true);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'error' => 'Error de validación: ' . $e->getMessage(),
                'status' => false
            ], 422);

        } catch (\Throwable $e) {
            // Log del error para debugging con más detalles
            Log::error('Error al generar factura electrónica', [
                'factura_id' => $request->id ?? 'no proporcionado',
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);

            // Mensaje más específico según el tipo de error
            $mensajeError = 'Error inesperado al generar la factura electrónica.';
            
            if (strpos($e->getMessage(), 'sign') !== false || strpos($e->getMessage(), 'certificate') !== false) {
                $mensajeError = 'Error al firmar la factura electrónica. Verifique que el certificado y la contraseña sean correctos.';
            } elseif (strpos($e->getMessage(), 'export') !== false) {
                $mensajeError = 'Error al exportar la factura electrónica. Verifique los permisos de escritura.';
            } elseif (strpos($e->getMessage(), 'XML') !== false || strpos($e->getMessage(), 'schema') !== false) {
                $mensajeError = 'Error en la estructura XML de la factura. Por favor, verifique los datos de la factura.';
            } else {
                $mensajeError = 'Error al generar la factura electrónica: ' . $e->getMessage();
            }

            return response()->json([
                'error' => $mensajeError,
                'status' => false
            ], 500);
        }
    }

    /**
     * Corrige el orden de los elementos en el XML de la factura electrónica
     * para cumplir con el esquema Facturae 3.2.1:
     * Orden requerido: InvoiceIssueData -> TaxesOutputs -> Items -> InvoiceTotals
     * Orden actual de la biblioteca: InvoiceIssueData -> TaxesOutputs -> InvoiceTotals -> Items
     */
    private function corregirOrdenXMLFactura($filePath)
    {
        try {
            if (!file_exists($filePath)) {
                return;
            }

            // Cargar el XML
            $dom = new \DOMDocument();
            $dom->preserveWhiteSpace = false;
            $dom->formatOutput = true;
            $dom->load($filePath);

            // Registrar el namespace
            $xpath = new \DOMXPath($dom);
            $xpath->registerNamespace('fe', 'http://www.facturae.es/Facturae/2014/v3.2.1/Facturae');

            // Buscar el elemento Invoice
            $invoiceNodes = $xpath->query('//fe:Invoice');
            if ($invoiceNodes->length === 0) {
                Log::warning('No se encontró el elemento Invoice en el XML');
                return;
            }

            $invoiceElement = $invoiceNodes->item(0);

            // Buscar los elementos que necesitamos reordenar
            $itemsElement = null;
            $taxesOutputsElement = null;
            $invoiceTotalsElement = null;
            $paymentDetailsElement = null;
            $legalLiteralsElement = null;

            foreach ($invoiceElement->childNodes as $child) {
                if ($child->nodeType === XML_ELEMENT_NODE) {
                    $tagName = $child->localName ?? $child->nodeName;
                    
                    if ($tagName === 'Items') {
                        $itemsElement = $child;
                    } elseif ($tagName === 'TaxesOutputs') {
                        $taxesOutputsElement = $child;
                    } elseif ($tagName === 'InvoiceTotals') {
                        $invoiceTotalsElement = $child;
                    } elseif ($tagName === 'PaymentDetails') {
                        $paymentDetailsElement = $child;
                    } elseif ($tagName === 'LegalLiterals') {
                        $legalLiteralsElement = $child;
                    }
                }
            }

            // El esquema Facturae 3.2.1 requiere este orden exacto según el error del validador:
            // InvoiceIssueData -> TaxesOutputs -> Items -> InvoiceTotals
            // La biblioteca genera: InvoiceIssueData -> TaxesOutputs -> InvoiceTotals -> Items
            
            // Obtener las posiciones actuales de los elementos
            $invoiceIssueDataPosition = -1;
            $itemsPosition = -1;
            $taxesOutputsPosition = -1;
            $totalsPosition = -1;
            $position = 0;
            
            // Recolectar todos los nodos en el orden actual
            $childNodes = [];
            foreach ($invoiceElement->childNodes as $child) {
                if ($child->nodeType === XML_ELEMENT_NODE) {
                    $tagName = $child->localName ?? $child->nodeName;
                    $childNodes[] = ['node' => $child, 'name' => $tagName, 'position' => $position];
                    
                    if ($tagName === 'InvoiceIssueData') {
                        $invoiceIssueDataPosition = $position;
                    } elseif ($tagName === 'Items') {
                        $itemsPosition = $position;
                    } elseif ($tagName === 'TaxesOutputs') {
                        $taxesOutputsPosition = $position;
                    } elseif ($tagName === 'InvoiceTotals') {
                        $totalsPosition = $position;
                    }
                    $position++;
                }
            }

            // Si no existe TaxesOutputs, intentar crearlo basándonos en los items o usar los totales
            // Normalmente la biblioteca lo genera automáticamente, pero verificamos si falta
            if (!$taxesOutputsElement && $itemsElement) {
                // Primero intentar leer TaxesOutputs que la biblioteca debería haber generado
                // Si no existe, la biblioteca puede haberlo omitido si no hay impuestos
                // En ese caso, verificar si realmente hay impuestos antes de crear
                
                $invoiceLines = $xpath->query('.//fe:InvoiceLine', $itemsElement);
                $hasTaxes = false;
                
                foreach ($invoiceLines as $line) {
                    $taxes = $xpath->query('.//fe:Tax', $line);
                    if ($taxes->length > 0) {
                        $hasTaxes = true;
                        break;
                    }
                }
                
                // Solo crear TaxesOutputs si realmente hay impuestos en las líneas
                if ($hasTaxes) {
                    $taxesOutputsElement = $dom->createElementNS('http://www.facturae.es/Facturae/2014/v3.2.1/Facturae', 'TaxesOutputs');
                    
                    // Recopilar todos los impuestos de las líneas agrupados por tipo y tasa
                    $taxesMap = []; // [tipo_rate] => [type, rate, base, amount]
                    
                    foreach ($invoiceLines as $line) {
                        // Obtener el total de la línea (TotalCost o calcular)
                        $totalCost = $xpath->evaluate('string(fe:TotalCost)', $line);
                        if (empty($totalCost)) {
                            $quantity = floatval($xpath->evaluate('string(fe:Quantity)', $line));
                            $unitPrice = floatval($xpath->evaluate('string(fe:UnitPriceWithoutTax)', $line));
                            $totalCost = $quantity * $unitPrice;
                        } else {
                            $totalCost = floatval($totalCost);
                        }
                        
                        // Buscar impuestos en la línea (pueden estar en TaxesOutputs o TaxesWithheld dentro de la línea)
                        $taxes = $xpath->query('.//fe:Tax', $line);
                        foreach ($taxes as $tax) {
                            $taxType = $xpath->evaluate('string(fe:TaxTypeCode)', $tax);
                            $taxRate = $xpath->evaluate('string(fe:TaxRate)', $tax);
                            
                            if (empty($taxType) || empty($taxRate)) {
                                continue;
                            }
                            
                            $taxKey = $taxType . '_' . $taxRate;
                            
                            if (!isset($taxesMap[$taxKey])) {
                                $taxesMap[$taxKey] = [
                                    'type' => $taxType,
                                    'rate' => floatval($taxRate),
                                    'base' => 0,
                                    'amount' => 0
                                ];
                            }
                            
                            $taxesMap[$taxKey]['base'] += $totalCost;
                            $taxesMap[$taxKey]['amount'] += ($totalCost * floatval($taxRate)) / 100;
                        }
                    }
                    
                    // Crear los elementos de impuestos
                    foreach ($taxesMap as $taxData) {
                        $tax = $dom->createElementNS('http://www.facturae.es/Facturae/2014/v3.2.1/Facturae', 'Tax');
                        
                        $taxTypeCode = $dom->createElementNS('http://www.facturae.es/Facturae/2014/v3.2.1/Facturae', 'TaxTypeCode', $taxData['type']);
                        $tax->appendChild($taxTypeCode);
                        
                        $taxRate = $dom->createElementNS('http://www.facturae.es/Facturae/2014/v3.2.1/Facturae', 'TaxRate', number_format($taxData['rate'], 2, '.', ''));
                        $tax->appendChild($taxRate);
                        
                        $taxableBase = $dom->createElementNS('http://www.facturae.es/Facturae/2014/v3.2.1/Facturae', 'TaxableBase');
                        $taxableBaseTotal = $dom->createElementNS('http://www.facturae.es/Facturae/2014/v3.2.1/Facturae', 'TotalAmount', number_format($taxData['base'], 2, '.', ''));
                        $taxableBase->appendChild($taxableBaseTotal);
                        $tax->appendChild($taxableBase);
                        
                        $taxAmount = $dom->createElementNS('http://www.facturae.es/Facturae/2014/v3.2.1/Facturae', 'TaxAmount');
                        $taxAmountTotal = $dom->createElementNS('http://www.facturae.es/Facturae/2014/v3.2.1/Facturae', 'TotalAmount', number_format($taxData['amount'], 2, '.', ''));
                        $taxAmount->appendChild($taxAmountTotal);
                        $tax->appendChild($taxAmount);
                        
                        $taxesOutputsElement->appendChild($tax);
                    }
                }
            }

            // Orden correcto según el error del validador:
            // InvoiceIssueData -> TaxesOutputs -> Items -> InvoiceTotals
            
            // Buscar InvoiceIssueData
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
            
            // Si no existe TaxesOutputs, crearlo vacío (el esquema lo requiere)
            $taxesOutputsCreado = false;
            if (!$taxesOutputsElement && ($itemsElement || $invoiceTotalsElement)) {
                Log::info('corregirOrdenXMLFactura: Creando TaxesOutputs vacío (requerido por esquema)');
                
                // Obtener el namespace del Invoice para usar el mismo
                $namespaceURI = $invoiceElement->namespaceURI;
                if ($namespaceURI) {
                    $taxesOutputsElement = $dom->createElementNS($namespaceURI, 'TaxesOutputs');
                } else {
                    $taxesOutputsElement = $dom->createElement('TaxesOutputs');
                }
                $taxesOutputsCreado = true;
            }
            
            // Verificar si necesitamos reordenar
            $necesitaReordenar = false;
            
            // Verificar orden: InvoiceIssueData < TaxesOutputs < Items < InvoiceTotals
            if ($invoiceIssueDataPosition !== -1) {
                if ($taxesOutputsPosition !== -1 && $taxesOutputsPosition <= $invoiceIssueDataPosition) {
                    $necesitaReordenar = true;
                }
                if ($itemsPosition !== -1 && $taxesOutputsPosition !== -1 && $itemsPosition < $taxesOutputsPosition) {
                    $necesitaReordenar = true;
                }
                if ($totalsPosition !== -1 && $itemsPosition !== -1 && $totalsPosition < $itemsPosition) {
                    $necesitaReordenar = true;
                }
            }
            
            if ($taxesOutputsCreado || ($itemsPosition !== -1 && $totalsPosition !== -1 && $totalsPosition < $itemsPosition)) {
                $necesitaReordenar = true;
            }
            
            if ($necesitaReordenar && $itemsElement && $invoiceTotalsElement) {
                Log::info('corregirOrdenXMLFactura: Reordenando elementos');
                
                // Remover elementos que necesitamos reordenar
                $nodesToReorder = [];
                
                if ($itemsElement->parentNode === $invoiceElement) {
                    $nodesToReorder['items'] = $itemsElement;
                    $invoiceElement->removeChild($itemsElement);
                }
                
                if ($taxesOutputsElement) {
                    if ($taxesOutputsElement->parentNode === $invoiceElement) {
                        $nodesToReorder['taxesOutputs'] = $taxesOutputsElement;
                        $invoiceElement->removeChild($taxesOutputsElement);
                    } else if ($taxesOutputsCreado) {
                        $nodesToReorder['taxesOutputs'] = $taxesOutputsElement;
                    }
                }
                
                if ($invoiceTotalsElement->parentNode === $invoiceElement) {
                    $nodesToReorder['invoiceTotals'] = $invoiceTotalsElement;
                    $invoiceElement->removeChild($invoiceTotalsElement);
                }
                
                // Reinsertar en el orden correcto: InvoiceIssueData -> TaxesOutputs -> Items -> InvoiceTotals
                $referenceNode = $invoiceIssueDataElement;
                
                // 1. Insertar TaxesOutputs después de InvoiceIssueData
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
                
                // 2. Insertar Items después de TaxesOutputs
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
                        $invoiceElement->appendChild($nodesToReorder['items']);
                    }
                    $referenceNode = $nodesToReorder['items'];
                }
                
                // 3. Insertar InvoiceTotals después de Items
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

            // Guardar el XML corregido
            $dom->save($filePath);
            
            Log::info('XML de factura corregido', [
                'archivo' => $filePath,
                'orden_original' => "Items:$itemsPosition, TaxesOutputs:$taxesOutputsPosition, InvoiceTotals:$totalsPosition"
            ]);

        } catch (\Exception $e) {
            Log::error('Error al corregir orden del XML de factura', [
                'archivo' => $filePath,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            // No lanzar excepción para no romper el flujo, solo loguear el error
        }
    }

    public function show(string $id)
    {
        $invoice = invoice::find($id);
        $empresa = CompanyDetails::find(1);
        $invoiceConcepts = InvoiceConcepts::where('invoice_id', $invoice->id)->get();


        return view('invoices.show', compact('invoice','empresa','invoiceConcepts'));
    }

}
