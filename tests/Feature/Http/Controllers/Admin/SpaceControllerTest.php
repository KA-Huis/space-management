<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Routing\UrlGenerator;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class SpaceControllerTest extends TestCase
{
    use RefreshDatabase;

    private UrlGenerator $urlGenerator;

    protected function setUp(): void
    {
        parent::setUp();

        $this->urlGenerator = $this->app->get(UrlGenerator::class);
    }

    public function testCreateFormIsShown(): void
    {
        // Given
        $user = User::factory()->create();

        $route = $this->urlGenerator->route('admin.space.create');

        // When
        $response = $this->actingAs($user)->get($route);

        // Then
        $response->assertOk();

        $response->assertInertia(fn (Assert $page) => $page
            ->component('Admin/Space/Create')
            ->has('user', fn (Assert $page) => $page
                ->where('full_name', $user->getFullName())
            )
        );
    }

    public function testThatANewSpaceCanBeCreated(): void
    {
        // Given
        $user = User::factory()->create();

        $formData = [
            'name'                     => 'Test',
            'description'              => 'Lorem ipsum',
            'is_open_for_reservations' => true,
        ];

        $route = $this->urlGenerator->route('admin.space.store');

        // When
        $response = $this->actingAs($user)->post($route, $formData);

        // Then
        $response->assertSessionHasNoErrors();
        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('spaces', $formData);
    }
}
