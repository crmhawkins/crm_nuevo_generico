<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Models\Company\CompanyDetails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;
use Illuminate\Support\Facades\File;

class SettingsController extends Controller
{
    public function index()
    {
        $configuracion = CompanyDetails::first();
        return view('settings.index', compact('configuracion'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'price_hour' => 'required|numeric',
            'logo' => 'nullable|image',
            'company_name' => 'required|string|max:255',
            'nif' => 'required|string|max:50',
            'address' => 'required|string|max:255',
            'bank_account_data' => 'nullable|string|max:255',
            'telephone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'certificado' => 'nullable|file',
            'contrasena' => 'nullable|string|min:6',
            'postCode' => 'nullable|string|max:255',
            'town' => 'nullable|string|max:255',
            'province' => 'nullable|string|max:255',
        ]);

        $data = $request->only([
            'price_hour', 'company_name', 'nif', 'address', 'bank_account_data', 'telephone', 'email','contrasena','postCode', 'town','province',
        ]);

        if ($request->hasFile('logo')) {
            try {
                $photo = $request->file('logo');
                
                // Validar que el archivo sea una imagen válida
                if (!$photo->isValid()) {
                    throw new \Exception('El archivo de imagen no es válido');
                }
                
                // Validar el tipo MIME
                $allowedMimes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp', 'image/svg+xml'];
                if (!in_array($photo->getMimeType(), $allowedMimes)) {
                    throw new \Exception('Tipo de archivo no permitido. Solo se permiten: JPG, PNG, GIF, WEBP, SVG');
                }
                
                // Validar el tamaño (máximo 5MB)
                if ($photo->getSize() > 5 * 1024 * 1024) {
                    throw new \Exception('El archivo es demasiado grande. Máximo 5MB permitido');
                }
                
                $path = public_path('assets/images/logo/logo.png');
                
                // Asegurar que el directorio existe
                $directory = dirname($path);
                if (!file_exists($directory)) {
                    mkdir($directory, 0755, true);
                }
                
                // Manejar SVG de forma diferente
                if ($photo->getMimeType() === 'image/svg+xml') {
                    // Para SVG, simplemente copiar el archivo
                    $svgPath = public_path('assets/images/logo/logo.svg');
                    copy($photo->getPathname(), $svgPath);
                    $data['logo'] = 'assets/images/logo/logo.svg';
                } else {
                    // Para imágenes raster, usar Intervention Image
                    try {
                        $manager = new ImageManager(new Driver());
                        $image = $manager->read($photo);
                        $image->toPng()->save($path);
                        $data['logo'] = 'assets/images/logo/logo.png';
                    } catch (\Exception $e) {
                        // Si falla Intervention Image, usar el método nativo de Laravel
                        $photo->move($directory, 'logo.png');
                        $data['logo'] = 'assets/images/logo/logo.png';
                    }
                }
                
            } catch (\Exception $e) {
                return redirect()->back()->with('toast', [
                    'icon' => 'error',
                    'mensaje' => 'Error al procesar la imagen: ' . $e->getMessage(),
                ])->withInput();
            }
        }

        // Guardar certificado
        if ($request->hasFile('certificado')) {
            $certificado = $request->file('certificado');
            $certName = random_int(0, 99999) . '-cert.' . $certificado->getClientOriginalExtension();
            $path = $certificado->storeAs('assets', $certName, 'public');
            $data['certificado'] = $path;
        }

        $savedConfig = CompanyDetails::create($data);

        if ($savedConfig) {
            return redirect()->route('configuracion.index')->with('toast',[
                'icon' => 'success',
                'mensaje' => 'Configuración creada correctamente.',
            ]);
        }else{
            return redirect()->route('configuracion.index')->with('toast',[
                'icon' => 'error',
                'mensaje' => 'Error al crear la configuración.',
            ]);
        }
    }

    public function update(Request $request, $id)
    {
        $configuracion = CompanyDetails::findOrFail($id);

        $request->validate([
            'price_hour' => 'nullable|numeric',
            'logo' => 'nullable|image|mimes:jpeg,jpg,png,gif,webp,svg|max:5120', // 5MB máximo
            'company_name' => 'nullable|string|max:255',
            'nif' => 'nullable|string|max:50',
            'address' => 'nullable|string|max:255',
            'bank_account_data' => 'nullable|string|max:255',
            'telephone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'certificado' => 'nullable|file',
            'contrasena' => 'nullable|string|min:6',
            'postCode' => 'nullable|string|max:255',
            'town' => 'nullable|string|max:255',
            'province' => 'nullable|string|max:255',
        ]);

        $data = $request->only([
            'price_hour', 'company_name', 'nif', 'address', 'bank_account_data', 'telephone', 'email', 'contrasena','postCode', 'town','province',
        ]);

         // Guardar logo
         if ($request->hasFile('logo')) {
            try {
                $photo = $request->file('logo');
                
                // Validar que el archivo sea una imagen válida
                if (!$photo->isValid()) {
                    throw new \Exception('El archivo de imagen no es válido');
                }
                
                // Validar el tipo MIME
                $allowedMimes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp', 'image/svg+xml'];
                if (!in_array($photo->getMimeType(), $allowedMimes)) {
                    throw new \Exception('Tipo de archivo no permitido. Solo se permiten: JPG, PNG, GIF, WEBP, SVG');
                }
                
                // Validar el tamaño (máximo 5MB)
                if ($photo->getSize() > 5 * 1024 * 1024) {
                    throw new \Exception('El archivo es demasiado grande. Máximo 5MB permitido');
                }
                
                // Eliminar logos anteriores
                $oldLogoPath = public_path('assets/images/logo/logo.png');
                $oldSvgPath = public_path('assets/images/logo/logo.svg');
                if (file_exists($oldLogoPath)) {
                    unlink($oldLogoPath);
                }
                if (file_exists($oldSvgPath)) {
                    unlink($oldSvgPath);
                }
                
                $path = public_path('assets/images/logo/logo.png');
                
                // Asegurar que el directorio existe
                $directory = dirname($path);
                if (!file_exists($directory)) {
                    mkdir($directory, 0755, true);
                }
                
                // Manejar SVG de forma diferente
                if ($photo->getMimeType() === 'image/svg+xml') {
                    // Para SVG, simplemente copiar el archivo
                    $svgPath = public_path('assets/images/logo/logo.svg');
                    copy($photo->getPathname(), $svgPath);
                    $data['logo'] = 'assets/images/logo/logo.svg';
                } else {
                    // Para imágenes raster, usar Intervention Image
                    try {
                        $manager = new ImageManager(new Driver());
                        $image = $manager->read($photo);
                        $image->toPng()->save($path);
                        $data['logo'] = 'assets/images/logo/logo.png';
                    } catch (\Exception $e) {
                        // Si falla Intervention Image, usar el método nativo de Laravel
                        $photo->move($directory, 'logo.png');
                        $data['logo'] = 'assets/images/logo/logo.png';
                    }
                }
                
            } catch (\Exception $e) {
                return redirect()->back()->with('toast', [
                    'icon' => 'error',
                    'mensaje' => 'Error al procesar la imagen: ' . $e->getMessage(),
                ])->withInput();
            }
        }

        // Guardar certificado
        if ($request->hasFile('certificado')) {
            if ($configuracion->certificado) {
                Storage::disk('public')->delete($configuracion->certificado);
            }
            $certificado = $request->file('certificado');
            $certName = random_int(0, 99999) . '-cert.' . $certificado->getClientOriginalExtension();
            $path = $certificado->storeAs('assets', $certName, 'public');
            $data['certificado'] = $path;
        }

        $updatedConfig = $configuracion->update($data);

        if ($updatedConfig) {
            return redirect()->route('configuracion.index')->with('toast',[
                'icon' => 'success',
                'mensaje' => 'Configuración actualizada correctamente.',
            ]);
        }else{
            return redirect()->route('configuracion.index')->with('toast',[
                'icon' => 'error',
                'mensaje' => 'Error al actualizar la configuración.',
            ]);
        }

    }
}
