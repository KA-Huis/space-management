<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controllers\Auth;

use App\Models\AuthorizedUser;
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
        // Given
        $route = $this->urlGenerator->route('auth.login');

        // When
        $response = $this->get($route);

        // Then
        $response->assertOk();
    }

    public function testUsersCanAuthenticateUsingTheLoginScreen(): void
    {
        // Given
        $user = AuthorizedUser::factory()->create();
        $route = $this->urlGenerator->route('auth.login');
        $formData = [
            'email' => $user->email,
            'password' => 'password',
        ];

        // When
        $response = $this->post($route, $formData);

        // Then
        $this->assertAuthenticated();

        $response->assertRedirect(RouteServiceProvider::HOME);
    }

    public function testUsersCanNotAuthenticateWithInvalidPassword(): void
    {
        // Given
        $user = AuthorizedUser::factory()->create();
        $route = $this->urlGenerator->route('auth.login');
        $formData = [
            'email' => $user->email,
            'password' => 'wrong-password',
        ];

        // When
        $response = $this->post($route, $formData);

        // Then
        $response->assertSessionHasErrors();

        $this->assertGuest();
    }

    public function testUsersCanLogoutFromTheirSession(): void
    {
        // Given
        $user = AuthorizedUser::factory()->create();

        $route = $this->urlGenerator->route('auth.logout');

        // When
        $response = $this->actingAs($user)->post($route);

        // Then
        $response->assertRedirect();

        $this->assertGuest();
    }
}
