<?php

namespace Tests\Feature\Http\Middleware;

use App\Http\Middleware\Authenticate;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Illuminate\Routing\Router;
use Illuminate\Routing\UrlGenerator;
use Tests\TestCase;

class AuthenticateTest extends TestCase
{
    use RefreshDatabase;

    private Router $router;
    private UrlGenerator $urlGenerator;

    protected function setUp(): void
    {
        parent::setUp();

        $this->router = $this->app->get(Router::class);
        $this->urlGenerator = $this->app->get(UrlGenerator::class);

        $this->router->middleware(Authenticate::class)->get('/_test/requires-authentication', function () {
            return new Response('OK', 200);
        });
    }

    public function testUserIsNotRedirectedWhenLoggedIn()
    {
        // Given
        $user = User::factory()->create();

        // When
        $response = $this->actingAs($user)->get('/_test/requires-authentication');

        // Then
        $response->assertOk();
        $response->assertSeeText('OK');
    }

    public function testUserIsRedirectedWhenNotLoggedIn()
    {
        // When
        $response = $this->get('/_test/requires-authentication');

        // Then
        $response->assertRedirect($this->urlGenerator->route('auth.login'));
    }
}
