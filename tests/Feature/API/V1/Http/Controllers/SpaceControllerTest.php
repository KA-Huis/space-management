<?php

declare(strict_types=1);

namespace Tests\Feature\API\V1\Http\Controllers;

use App\Authentication\Guards\RestApiGuard;
use App\Models\ReparationRequest;
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

    public function testDestroyEndpoint(): void
    {
        // Given
        $user = User::factory()->create();

        $space = Space::factory(3)
            ->create();
        /** @var ReparationRequest $firstSpace */
        $firstSpace = $space->first();

        $endpointUri = $this->urlGenerator->route('api.v1.space.destroy', [
            'space' => $firstSpace->id,
        ]);

        // When
        $response = $this
            ->actingAs($user, (new RestApiGuard())->getName())
            ->delete($endpointUri);

        // Then
        self::assertFalse($firstSpace->trashed());

        $response->assertOk();
    }
}
