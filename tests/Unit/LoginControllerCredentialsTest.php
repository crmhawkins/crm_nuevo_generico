<?php

namespace Tests\Unit;

use App\Http\Controllers\Auth\LoginController;
use Illuminate\Http\Request;
use ReflectionMethod;
use Tests\TestCase;

class LoginControllerCredentialsTest extends TestCase
{
    public function test_login_credentials_accept_username(): void
    {
        $credentials = $this->resolveCredentials([
            'username' => 'admin',
            'password' => 'secret',
        ]);

        $this->assertSame([
            'username' => 'admin',
            'password' => 'secret',
            'inactive' => 0,
        ], $credentials);
    }

    public function test_login_credentials_accept_email(): void
    {
        $credentials = $this->resolveCredentials([
            'username' => 'admin@example.com',
            'password' => 'secret',
        ]);

        $this->assertSame([
            'email' => 'admin@example.com',
            'password' => 'secret',
            'inactive' => 0,
        ], $credentials);
    }

    private function resolveCredentials(array $payload): array
    {
        $controller = new LoginController();
        $method = new ReflectionMethod($controller, 'credentials');
        $method->setAccessible(true);

        return $method->invoke($controller, Request::create('/login', 'POST', $payload));
    }
}
