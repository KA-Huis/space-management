<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controllers\Auth;

use App\Authentication\GuardsInterface;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Routing\UrlGenerator;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    private UrlGenerator $urlGenerator;

    protected function setUp(): void
    {
        parent::setUp();

        $this->urlGenerator = $this->app->get(UrlGenerator::class);
    }

    public function testRegistrationScreenCanBeRendered(): void
    {
        // When
        $response = $this->get('/register');

        // Then
        $response->assertOk();
    }

    public function testNewUsersCanRegister(): void
    {
        // Given
        $email = 'test@example.com';
        $formData = [
            'first_name'            => 'Test',
            'last_name'             => 'User',
            'email'                 => $email,
            'password'              => 'password',
            'password_confirmation' => 'password',
        ];

        // When
        $route = $this->urlGenerator->route('auth.register');
        $response = $this->post($route, $formData);

        // Then
        $user = User::where('email', '=', $email)->first();

        $response->assertSessionDoesntHaveErrors();
        $response->assertRedirect($this->urlGenerator->route('admin.dashboard'));

        $this->assertAuthenticatedAs($user, GuardsInterface::WEB);
    }
}
