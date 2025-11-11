<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }
    
    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $e
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Throwable
     */
    public function render($request, Throwable $e)
    {
        // Manejar error 419 (CSRF token expired)
        if ($e instanceof \Illuminate\Session\TokenMismatchException) {
            if ($request->is('fichaje/*')) {
                return redirect()->route('fichaje.login')
                    ->withErrors(['login' => 'La sesión ha expirado. Por favor, intenta iniciar sesión nuevamente.'])
                    ->withInput();
            }
        }
        
        return parent::render($request, $e);
    }
}
