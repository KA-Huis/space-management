<?php

namespace Tests\Feature\Http\Controllers\Auth;

use App\Models\User;
use App\Notifications\Auth\ResetPassword;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Routing\UrlGenerator;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class PasswordResetTest extends TestCase
{
    use RefreshDatabase;

    private UrlGenerator $urlGenerator;

    protected function setUp(): void
    {
        parent::setUp();

        $this->urlGenerator = $this->app->get(UrlGenerator::class);
    }

    public function test_reset_password_link_screen_can_be_rendered(): void
    {
        // Given
        $route = $this->urlGenerator->route('auth.password.request');

        // When
        $response = $this->get($route);

        // Then
        $response->assertOk();
    }

    public function test_reset_password_link_can_be_requested(): void
    {
        Notification::fake();

        // Given
        $user = User::factory()->create();
        $formData = [
            'email' => $user->email,
        ];
        $route = $this->urlGenerator->route('auth.password.request');

        // When
        $this->post($route, $formData);

        // Then
        Notification::assertSentTo($user, ResetPassword::class);
    }

    public function test_reset_password_screen_can_be_rendered(): void
    {
        Notification::fake();

        // Given
        $user = User::factory()->create();
        $formData = [
            'email' => $user->email,
        ];
        $route = $this->urlGenerator->route('auth.password.request', []);

        // When
        $this->post($route, $formData);

        // Then
        Notification::assertSentTo($user, ResetPassword::class, function ($notification) {
            $route = $this->urlGenerator->route('auth.password.reset', [
                'token' => $notification->getToken(),
            ]);

            $response = $this->get($route);

            $response->assertOk();

            return true;
        });
    }

    public function test_password_can_be_reset_with_valid_token(): void
    {
        Notification::fake();

        // Given
        $user = User::factory()->create();
        $formData = [
            'email' => $user->email,
        ];
        $route = $this->urlGenerator->route('auth.password.request');

        // When
        $this->post($route, $formData);

        // Then
        Notification::assertSentTo($user, ResetPassword::class, function ($notification) use ($user) {
            $route = $this->urlGenerator->route('auth.password.update');
            $formData = [
                'token' => $notification->getToken(),
                'email' => $user->email,
                'password' => 'password',
                'password_confirmation' => 'password',
            ];

            $response = $this->post($route, $formData);

            $response->assertSessionHasNoErrors();

            return true;
        });
    }
}
