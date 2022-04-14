<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Routing\UrlGenerator;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class DashboardControllerTest extends TestCase
{
    use RefreshDatabase;

    private UrlGenerator $urlGenerator;

    protected function setUp(): void
    {
        parent::setUp();

        $this->urlGenerator = $this->app->get(UrlGenerator::class);
    }

    public function testDashboardIsShown(): void
    {
        // Given
        $user = User::factory()->create();

        $route = $this->urlGenerator->route('admin.dashboard');

        // When
        $response = $this->actingAs($user)->get($route);

        // Then
        $response->assertOk();

        $response->assertInertia(fn (Assert $page) => $page
            ->component('Admin/Dashboard')
            ->has('user', fn (Assert $page) => $page
                ->where('full_name', $user->getFullName())
            )
        );
    }
}
