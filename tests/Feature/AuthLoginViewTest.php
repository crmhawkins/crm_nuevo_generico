<?php

namespace Tests\Feature;

use Tests\TestCase;

class AuthLoginViewTest extends TestCase
{
    public function test_login_page_displays_error_messages(): void
    {
        $errors = new \Illuminate\Support\ViewErrorBag();
        $errors->put('default', new \Illuminate\Support\MessageBag([
            'username' => ['Credenciales incorrectas.'],
        ]));

        $response = $this->withSession([
            'errors' => $errors,
        ])->get('/login');

        $response->assertOk();
        $response->assertSee('Credenciales incorrectas.');
        $response->assertSee('Usuario o Email');
    }
}
