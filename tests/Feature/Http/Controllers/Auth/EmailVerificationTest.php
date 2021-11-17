<?php

namespace Tests\Feature\Http\Controllers\Auth;

use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Routing\UrlGenerator;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\URL;
use Tests\TestCase;

class EmailVerificationTest extends TestCase
{
    use RefreshDatabase;

    private UrlGenerator $urlGenerator;

    protected function setUp(): void
    {
        parent::setUp();

        $this->urlGenerator = $this->app->get(UrlGenerator::class);
    }

    public function test_email_verification_screen_can_be_rendered(): void
    {
        // Given
        $user = User::factory()->create([
            'email_verified_at' => null,
        ]);

        $route = $this->urlGenerator->route('auth.verification.notice');

        // When
        $response = $this->actingAs($user)->get($route);

        // Then
        $response->assertOk();
    }

    public function test_email_verification_screen_is_not_rendered_when_email_is_already_verified(): void
    {
        $this->markTestSkipped();

        // Given
        $user = User::factory()->create([
            'email_verified_at' => Carbon::now(),
        ]);

        $route = $this->urlGenerator->route('auth.verification.notice');

        // When
        $response = $this->actingAs($user)->get($route);

        // Then
        $response->assertRedirect($this->urlGenerator->route('admin.dashboard'));
    }

    public function test_email_can_be_verified(): void
    {
        $this->markTestSkipped();

        Event::fake();

        // Given
        $user = User::factory()->create([
            'email_verified_at' => null,
        ]);

        $verificationUrl = $this->urlGenerator->temporarySignedRoute(
            'verification.verify',
            Carbon::now()->addMinutes(60),
            ['id' => $user->id, 'hash' => sha1($user->email)]
        );

        // When
        $response = $this->actingAs($user)->get($verificationUrl);

        // Then
        $response->assertRedirect(RouteServiceProvider::HOME.'?verified=1');

        Event::assertDispatched(Verified::class);

        self::assertTrue($user->fresh()->hasVerifiedEmail());
    }

    public function test_email_is_not_verified_with_invalid_hash()
    {
        $this->markTestSkipped();

        $user = User::factory()->create([
            'email_verified_at' => null,
        ]);

        $verificationUrl = URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(60),
            ['id' => $user->id, 'hash' => sha1('wrong-email')]
        );

        $this->actingAs($user)->get($verificationUrl);

        $this->assertFalse($user->fresh()->hasVerifiedEmail());
    }
}
