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

    public function test_login_screen_can_be_rendered(): void
    {
        // When
        $route = $this->urlGenerator->route('auth.login');
        $response = $this->get($route);

        // Then
        $response->assertOk();
    }

    public function test_users_can_authenticate_using_the_login_screen(): void
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

    public function test_users_can_not_authenticate_with_invalid_password(): void
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
