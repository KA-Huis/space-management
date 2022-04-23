<?php

declare(strict_types=1);

namespace Tests\Feature\API\V1\Http\Controllers;

use App\Authentication\GuardsInterface;
use App\Models\Enums\ReparationRequestPriority;
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
            ->actingAs($user, GuardsInterface::REST_API)
            ->get($endpointUri);

        // Then
        $firstReparationRequest = $reparationRequests->first();

        $response->assertOk()
            ->assertJsonPaginated()
            ->assertJson(
                fn (AssertableJson $json) => $json
                    ->has('data', 3,
                        fn (AssertableJson $json) => $json
                            ->where('id', $firstReparationRequest->id)
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
            ->actingAs($user, GuardsInterface::REST_API)
            ->get($endpointUri);

        // Then
        $response->assertOk()
            ->assertJsonPaginated()
            ->assertJson(
                fn (AssertableJson $json) => $json
                    ->has('data', 1,
                        fn (AssertableJson $json) => $json
                            ->where('id', $expectedReparationRequest->id)
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
            ->actingAs($user, GuardsInterface::REST_API)
            ->get($endpointUri);

        // Then
        $response->assertOk()
            ->assertJsonPaginated()
            ->assertJson(
                fn (AssertableJson $json) => $json
                    ->has('data', 1,
                        fn (AssertableJson $json) => $json
                            ->where('id', $expectedReparationRequest->id)
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
            ->actingAs($user, GuardsInterface::REST_API)
            ->get($endpointUri);

        // Then
        $response->assertOk()
            ->assertJsonPaginated()
            ->assertJson(
                fn (AssertableJson $json) => $json
                    ->has('data', 1,
                        fn (AssertableJson $json) => $json
                            ->where('id', $expectedReparationRequest->id)
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
            ->actingAs($user, GuardsInterface::REST_API)
            ->get($endpointUri);

        // Then
        $response->assertOk()
            ->assertJsonPaginated()
            ->assertJson(
                fn (AssertableJson $json) => $json
                    ->has('data', 1,
                        fn (AssertableJson $json) => $json
                            ->where('id', $expectedReparationRequest->id)
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
            ->actingAs($user, GuardsInterface::REST_API)
            ->get($endpointUri);

        // Then
        $response->assertOk()
            ->assertJsonPaginated();

        // Assert that the order of resources are according to the expected sort criteria
        self::assertEquals(
            ReparationRequest::orderBy($dateColumn)->pluck('id'),
            (new Collection($response->decodeResponseJson()['data']))
                ->map(function (array $resource) {
                    return $resource['id'];
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
            ->actingAs($user, GuardsInterface::REST_API)
            ->get($endpointUri);

        // Then
        $response->assertOk()
            ->assertJsonPaginated()
            ->assertJson(
                fn (AssertableJson $json) => $json
                    ->has('data', 1,
                        fn (AssertableJson $json) => $json
                            ->where('id', $reparationRequest->id)
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
            ->actingAs($user, GuardsInterface::REST_API)
            ->get($endpointUri);

        // Then
        $response->assertOk()
            ->assertJsonPaginated()
            ->assertJson(
                fn (AssertableJson $json) => $json
                    ->has('data', 1,
                        fn (AssertableJson $json) => $json
                            ->where('id', $reparationRequest->id)
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
            ->actingAs($user, GuardsInterface::REST_API)
            ->get($endpointUri);

        // Then
        $response->assertOk()
            ->assertJsonPaginated()
            ->assertJson(
                fn (AssertableJson $json) => $json
                    ->has('data', 1,
                        fn (AssertableJson $json) => $json
                            ->where('id', $reparationRequest->id)
                            ->has('materials', 3)
                            ->etc()
                    )
                    ->etc()
            );
    }

    public function testShowEndpoint(): void
    {
        // Given
        $user = User::factory()->create();

        $reparationRequest = ReparationRequest::factory()
            ->for(User::factory(), 'reporter')
            ->create();

        $endpointUri = $this->urlGenerator->route('api.v1.reparation-request.show', [
            'reparationRequest' => $reparationRequest->id,
        ]);

        // When
        $response = $this
            ->actingAs($user, GuardsInterface::REST_API)
            ->get($endpointUri);

        // Then
        $response->assertOk()
            ->assertJson(
                fn (AssertableJson $json) => $json
                    ->has('data',
                        fn (AssertableJson $json) => $json
                            ->where('id', $reparationRequest->id)
                            ->etc()
                    )
                    ->etc()
            );
    }

    public function testDestroyEndpoint(): void
    {
        // Given
        $user = User::factory()->create();

        $reparationRequests = ReparationRequest::factory(3)
            ->for(User::factory(), 'reporter')
            ->create();
        /** @var ReparationRequest $firstReparationRequest */
        $firstReparationRequest = $reparationRequests->first();

        $endpointUri = $this->urlGenerator->route('api.v1.reparation-request.destroy', [
            'reparationRequest' => $firstReparationRequest->id,
        ]);

        // When
        $response = $this
            ->actingAs($user, GuardsInterface::REST_API)
            ->delete($endpointUri);

        // Then
        self::assertFalse($firstReparationRequest->trashed());

        $response->assertOk();
    }

    public function testStoreEndpoint(): void
    {
        // Given
        $user = User::factory()->create();

        $data = [
            'title'                 => 'Some title',
            'description'           => 'A description that is does not add anything of value.',
            'priority'              => ReparationRequestPriority::PRIORITY_HIGH,
        ];

        $endpointUri = $this->urlGenerator->route('api.v1.reparation-request.store');

        // When
        $response = $this
            ->actingAs($user, GuardsInterface::REST_API)
            ->post($endpointUri, $data);

        // Then
        $reparationRequest = ReparationRequest::where('title', '=', $data['title'])->latest()->first();

        $response->assertCreated()
            ->assertJsonPath('data.id', $reparationRequest->id);
    }

    public function testStoreEndpointValidation(): void
    {
        // Given
        $user = User::factory()->create();

        $data = [
            'description'           => 'A description that is does not add anything of value.',
            'priority'              => -200,
        ];

        $endpointUri = $this->urlGenerator->route('api.v1.reparation-request.store');

        // When
        $response = $this
            ->actingAs($user, GuardsInterface::REST_API)
            ->post($endpointUri, $data);

        // Then
        $response->assertRedirect()
            ->assertSessionHasErrors([
                'title',
                'priority',
            ]);
    }
}
