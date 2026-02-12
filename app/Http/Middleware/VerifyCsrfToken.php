<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    protected $except = [
        '/transcribir-acta',
        '/api/getAyudas',
        '/api/updateAyudas/{id}',
        '/api/updateMensajes',
        '/api/Clientes',
        '/api/Presupuestos',
        '/api/Facturas',
        '/api/Proyectos',
        '/api/Servicios',
        '/api/addBeneficiarioName',
    ];
}
