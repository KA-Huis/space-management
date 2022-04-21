<?php

declare(strict_types=1);

namespace Tests\Feature\API\V1\Http\Controllers;

use App\Models\ReparationRequest;
use App\Models\ReparationRequestMaterial;
use App\Models\ReparationRequestStatus;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Routing\UrlGenerator;
use Illuminate\Support\Collection;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class ReparationRequestControllerTest extends TestCase
{
    use RefreshDatabase;

    private UrlGenerator $urlGenerator;

    protected function setUp(): void
    {
        parent::setUp();

        $this->urlGenerator = $this->app->get(UrlGenerator::class);
    }

    public function testIndexEndpoint(): void
    {
        // Given
        $user = User::factory()->create();

        $reparationRequests = ReparationRequest::factory(3)
            ->for(User::factory(), 'reporter')
            ->create();

        $endpointUri = $this->urlGenerator->route('api.v1.reparation-request.index');

        // When
        $response = $this
            ->actingAs($user)
            ->get($endpointUri);

        // Then
        $firstReparationRequest = $reparationRequests->first();

        $response->assertOk()
            ->assertJsonPaginated()
            ->assertJson(
                fn (AssertableJson $json) => $json
                    ->has('data', 3,
                        fn (AssertableJson $json) => $json
                            ->where('uuid', $firstReparationRequest->uuid)
                            ->etc()
                    )
                    ->etc()
            );
    }

    public function testIndexEndpointFilteringOnUuid(): void
    {
        // Given
        $user = User::factory()->create();

        $reparationRequests = ReparationRequest::factory(3)
            ->for(User::factory(), 'reporter')
            ->create();
        $expectedReparationRequest = $reparationRequests->random();

        $endpointUri = $this->urlGenerator->route('api.v1.reparation-request.index', [
            'filter' => [
                'uuid' => $expectedReparationRequest->uuid,
            ],
        ]);

        // When
        $response = $this
            ->actingAs($user)
            ->get($endpointUri);

        // Then
        $response->assertOk()
            ->assertJsonPaginated()
            ->assertJson(
                fn (AssertableJson $json) => $json
                    ->has('data', 1,
                        fn (AssertableJson $json) => $json
                            ->where('uuid', $expectedReparationRequest->uuid)
                            ->etc()
                    )
                    ->etc()
            );
    }

    public function testIndexEndpointFilteringOnTitle(): void
    {
        // Given
        $user = User::factory()->create();

        $reparationRequests = ReparationRequest::factory(3)
            ->for(User::factory(), 'reporter')
            ->create([
                'title' => 'Unrelevant title',
            ]);
        $expectedReparationRequest = $reparationRequests->random();
        $expectedReparationRequest->title = 'Needle in title';
        $expectedReparationRequest->save();

        $endpointUri = $this->urlGenerator->route('api.v1.reparation-request.index', [
            'filter' => [
                'title' => 'Needle',
            ],
        ]);

        // When
        $response = $this
            ->actingAs($user)
            ->get($endpointUri);

        // Then
        $response->assertOk()
            ->assertJsonPaginated()
            ->assertJson(
                fn (AssertableJson $json) => $json
                    ->has('data', 1,
                        fn (AssertableJson $json) => $json
                            ->where('uuid', $expectedReparationRequest->uuid)
                            ->etc()
                    )
                    ->etc()
            );
    }

    public function testIndexEndpointFilteringOnDescription(): void
    {
        // Given
        $user = User::factory()->create();

        $reparationRequests = ReparationRequest::factory(3)
            ->for(User::factory(), 'reporter')
            ->create([
                'description' => 'Unrelevant description',
            ]);
        $expectedReparationRequest = $reparationRequests->random();
        $expectedReparationRequest->description = 'Needle in description';
        $expectedReparationRequest->save();

        $endpointUri = $this->urlGenerator->route('api.v1.reparation-request.index', [
            'filter' => [
                'description' => 'Needle',
            ],
        ]);

        // When
        $response = $this
            ->actingAs($user)
            ->get($endpointUri);

        // Then
        $response->assertOk()
            ->assertJsonPaginated()
            ->assertJson(
                fn (AssertableJson $json) => $json
                    ->has('data', 1,
                        fn (AssertableJson $json) => $json
                            ->where('uuid', $expectedReparationRequest->uuid)
                            ->etc()
                    )
                    ->etc()
            );
    }

    public function testIndexEndpointFilteringOnPriority(): void
    {
        // Given
        $user = User::factory()->create();

        $reparationRequests = ReparationRequest::factory(3)
            ->for(User::factory(), 'reporter')
            ->create([
                'priority' => 0,
            ]);
        $expectedReparationRequest = $reparationRequests->random();
        $expectedReparationRequest->priority = 1;
        $expectedReparationRequest->save();

        $endpointUri = $this->urlGenerator->route('api.v1.reparation-request.index', [
            'filter' => [
                'priority' => 1,
            ],
        ]);

        // When
        $response = $this
            ->actingAs($user)
            ->get($endpointUri);

        // Then
        $response->assertOk()
            ->assertJsonPaginated()
            ->assertJson(
                fn (AssertableJson $json) => $json
                    ->has('data', 1,
                        fn (AssertableJson $json) => $json
                            ->where('uuid', $expectedReparationRequest->uuid)
                            ->etc()
                    )
                    ->etc()
            );
    }

    private function indexEndpointSortingOnDateColumnsProvider(): array
    {
        return [
            [
                'column' => 'created_at',
            ],
            [
                'column' => 'updated_at',
            ],
        ];
    }

    /**
     * @dataProvider indexEndpointSortingOnDateColumnsProvider
     */
    public function testIndexEndpointSortingOnDateColumns(string $dateColumn): void
    {
        // Given
        $user = User::factory()->create();

        ReparationRequest::factory(3)
            ->for(User::factory(), 'reporter')
            ->create();

        $endpointUri = $this->urlGenerator->route('api.v1.reparation-request.index', [
            'sort' => [
                $dateColumn,
            ],
        ]);

        // When
        $response = $this
            ->actingAs($user)
            ->get($endpointUri);

        // Then
        $response->assertOk()
            ->assertJsonPaginated();

        // Assert that the order of resources are according to the expected sort criteria
        self::assertEquals(
            ReparationRequest::orderBy($dateColumn)->pluck('uuid'),
            (new Collection($response->decodeResponseJson()['data']))
                ->map(function (array $resource) {
                    return $resource['uuid'];
                })
        );
    }

    public function testIndexEndpointIncludingReporter(): void
    {
        // Given
        $user = User::factory()->create();

        $reparationRequest = ReparationRequest::factory()
            ->for(User::factory(), 'reporter')
            ->create();

        $endpointUri = $this->urlGenerator->route('api.v1.reparation-request.index', [
            'include' => [
                'reporter',
            ],
        ]);

        // When
        $response = $this
            ->actingAs($user)
            ->get($endpointUri);

        // Then
        $response->assertOk()
            ->assertJsonPaginated()
            ->assertJson(
                fn (AssertableJson $json) => $json
                    ->has('data', 1,
                        fn (AssertableJson $json) => $json
                            ->where('uuid', $reparationRequest->uuid)
                            ->has('reporter')
                            ->etc()
                    )
                    ->etc()
            );
    }

    public function testIndexEndpointIncludingStatuses(): void
    {
        // Given
        $user = User::factory()->create();

        $reparationRequest = ReparationRequest::factory()
            ->for(User::factory(), 'reporter')
            ->has(ReparationRequestStatus::factory()->count(3), 'statuses')
            ->create();

        $endpointUri = $this->urlGenerator->route('api.v1.reparation-request.index', [
            'include' => [
                'statuses',
            ],
        ]);

        // When
        $response = $this
            ->actingAs($user)
            ->get($endpointUri);

        // Then
        $response->assertOk()
            ->assertJsonPaginated()
            ->assertJson(
                fn (AssertableJson $json) => $json
                    ->has('data', 1,
                        fn (AssertableJson $json) => $json
                            ->where('uuid', $reparationRequest->uuid)
                            ->has('statuses', 3)
                            ->etc()
                    )
                    ->etc()
            );
    }

    public function testIndexEndpointIncludingMaterials(): void
    {
        // Given
        $user = User::factory()->create();

        $reparationRequest = ReparationRequest::factory()
            ->for(User::factory(), 'reporter')
            ->has(ReparationRequestMaterial::factory()->count(3), 'materials')
            ->create();

        $endpointUri = $this->urlGenerator->route('api.v1.reparation-request.index', [
            'include' => [
                'materials',
            ],
        ]);

        // When
        $response = $this
            ->actingAs($user)
            ->get($endpointUri);

        // Then
        $response->assertOk()
            ->assertJsonPaginated()
            ->assertJson(
                fn (AssertableJson $json) => $json
                    ->has('data', 1,
                        fn (AssertableJson $json) => $json
                            ->where('uuid', $reparationRequest->uuid)
                            ->has('materials', 3)
                            ->etc()
                    )
                    ->etc()
            );
    }

    public function testShowEndpoint(): void
    {
        // Given
        $reparationRequest = ReparationRequest::factory()
            ->for(User::factory(), 'reporter')
            ->create();

        $endpointUri = $this->urlGenerator->route('api.v1.reparation-request.show', [
            'reparationRequest' => $reparationRequest->id,
        ]);

        // When
        $response = $this->get($endpointUri);

        // Then
        $response->assertOk()
            ->assertJson(
                fn (AssertableJson $json) => $json
                    ->has('data',
                        fn (AssertableJson $json) => $json
                            ->where('uuid', $reparationRequest->uuid)
                            ->etc()
                    )
                    ->etc()
            );
    }
}
