<?php

namespace Tests\Feature\Http\Controllers\Auth;

use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Routing\UrlGenerator;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    private UrlGenerator $urlGenerator;

    protected function setUp(): void
    {
        parent::setUp();

        $this->urlGenerator = $this->app->get(UrlGenerator::class);
    }

    public function testLoginScreenCanBeRendered(): void
    {
        // When
        $route = $this->urlGenerator->route('auth.login');
        $response = $this->get($route);

        // Then
        $response->assertOk();
    }

    public function testUsersCanAuthenticateUsingTheLoginScreen(): void
    {
        // Given
        $user = User::factory()->create();

        // When
        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        // Then
        $this->assertAuthenticated();

        $response->assertRedirect(RouteServiceProvider::HOME);
    }

    public function testUsersCanNotAuthenticateWithInvalidPassword(): void
    {
        // Given
        $user = User::factory()->create();

        // When
        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'wrong-password',
        ]);

        // Then
        $response->assertSessionHasErrors();

        $this->assertGuest();
    }
}
