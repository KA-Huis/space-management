<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Routing\UrlGenerator;
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

    public function test_create_form_is_shown(): void
    {
        // Given
        $user = User::factory()->create();

        $route = $this->urlGenerator->route('admin.space.create');

        // When
        $response = $this->actingAs($user)->get($route);

        // Then
        $response->assertOk();
    }

    public function test_that_a_new_space_can_be_created(): void
    {
        // Given
        $user = User::factory()->create();

        $formData = [
            'name' => 'Test',
            'description' => 'Lorem ipsum',
            'is_open_for_reservations' => true,
        ];

        $route = $this->urlGenerator->route('admin.space.store');;

        // When
        $response = $this->actingAs($user)->post($route, $formData);

        // Then
        $response->assertSessionHasNoErrors();
        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('spaces', $formData);
    }
}
