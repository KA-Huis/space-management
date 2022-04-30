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

    public function testStoreEndpoint(): void
    {
        // Given
        $user = User::factory()->create();

        $data = [
            'name'                                  => 'Some name',
            'description'                           => 'A description that is does not add anything of value.',
            'is_open_for_reservations'              => true,
        ];

        $endpointUri = $this->urlGenerator->route('api.v1.space.store');

        // When
        $response = $this
            ->actingAs($user, (new RestApiGuard())->getName())
            ->post($endpointUri, $data);

        // Then
        $space = Space::where('name', '=', $data['name'])->latest()->first();

        $response->assertCreated()
            ->assertJsonPath('data.id', $space->id);
    }

    public function testStoreEndpointValidation(): void
    {
        // Given
        $user = User::factory()->create();

        $data = [
            'description'                           => 'A description that is does not add anything of value.',
            'is_open_for_reservations'              => -200,
        ];

        $endpointUri = $this->urlGenerator->route('api.v1.space.store');

        // When
        $response = $this
            ->actingAs($user, (new RestApiGuard())->getName())
            ->post($endpointUri, $data);

        // Then
        $response->assertRedirect()
            ->assertSessionHasErrors([
                'name',
                'is_open_for_reservations',
            ]);
    }
}
