<?php

declare(strict_types=1);

namespace Tests\Feature\API\V1\Http\Controllers;

use App\Authentication\Guards\RestApiGuard;
use App\Models\Space;
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

    public function testUpdateEndpoint(): void
    {
        // Given
        $user = User::factory()->create();
        $space = Space::create([
            'name' => 'Some name',
            'description' => 'A description that is does not add anything of value.',
            'is_open_for_reservations' => true,
        ]);

        $newData = [
            'name' => 'New name',
            'description' => 'Edited description',
            'is_open_for_reservations' => false,
        ];

        $endpointUri = $this->urlGenerator->route('api.v1.space.update', [
            'space' => $space->id,
        ]);

        // When
        $response = $this
            ->actingAs($user, (new RestApiGuard())->getName())
            ->put($endpointUri, $newData);

        // Then
        $space->refresh();

        $response->assertSuccessful()
            ->assertJsonPath('data.id', $space->id);

        self::assertEquals($space->title, $newData['name']);
        self::assertEquals($space->description, $newData['description']);
        self::assertEquals($space->priority, $newData['is_open_for_reservations']);
    }

    public function testUpdateEndpointValidation(): void
    {
        // Given
        $user = User::factory()->create();
        $space = Space::create([
            'name' => 'Some name',
            'description' => 'A description that is does not add anything of value.',
            'is_open_for_reservations' => true,
        ]);

        $newData = [
            'description' => 'Edited description',
            'is_open_for_reservations' => null,
        ];

        $endpointUri = $this->urlGenerator->route('api.v1.spacet.update', [
            'reparationRequest' => $space->id,
        ]);

        // When
        $response = $this
            ->actingAs($user, (new RestApiGuard())->getName())
            ->put($endpointUri, $newData);

        // Then
        $response->assertRedirect()
            ->assertSessionHasErrors([
                'name',
                'is_open_for_reservations',
            ]);
    }
}
