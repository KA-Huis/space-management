<?php

declare(strict_types=1);

namespace Tests\Feature\API\V1\Http\Controllers;

use App\Authentication\Guards\RestApiGuard;
use App\Models\Space;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Routing\UrlGenerator;
use Illuminate\Testing\Fluent\AssertableJson;
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

    public function testShowEndpoint(): void
    {
        // Given
        $user = User::factory()->create();

        $space = Space::factory()
            ->create();

        $endpointUri = $this->urlGenerator->route('api.v1.space.show', [
            'space' => $space->id,
        ]);

        // When
        $response = $this
            ->actingAs($user, (new RestApiGuard())->getName())
            ->get($endpointUri);

        // Then
        $response->assertOk()
            ->assertJson(
                fn (AssertableJson $json) => $json
                    ->has('data',
                        fn (AssertableJson $json) => $json
                            ->where('id', $space->id)
                            ->etc()
                    )
                    ->etc()
            );
    }
}
