<?php

declare(strict_types=1);

namespace Feature\API\V1\Http\Controllers;

use App\Models\ReparationRequest;
use App\Models\ReparationRequestMaterial;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Routing\UrlGenerator;
use Illuminate\Support\Collection;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class ReparationRequestMaterialControllerTest extends TestCase
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
        $reparationMaterialRequests = ReparationRequestMaterial::factory(3)
            ->for(ReparationRequest::factory()
                ->for(User::factory(), 'reporter')
            )
            ->create();

        $endpointUri = $this->urlGenerator->route('api.v1.reparation-request-material.index');

        // When
        $response = $this->get($endpointUri);

        // Then
        $firstReparationRequest = $reparationMaterialRequests->first();

        $response->assertOk()
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
        $reparationRequests = ReparationRequestMaterial::factory(3)
            ->for(ReparationRequest::factory()
                ->for(User::factory(), 'reporter')
            )
            ->create();

        $expectedReparationRequest = $reparationRequests->random();

        $endpointUri = $this->urlGenerator->route('api.v1.reparation-request-material.index', [
            'filter' => [
                'uuid' => $expectedReparationRequest->uuid,
            ],
        ]);

        // When
        $response = $this->get($endpointUri);

        // Then
        $response->assertOk()
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

    public function testIndexEndpointFilteringOnName(): void
    {
        // Given
        $reparationRequestMaterials = ReparationRequestMaterial::factory(3)
            ->for(ReparationRequest::factory()
                ->for(User::factory(), 'reporter')
            )
            ->create([
                'name' => 'Unrelevant title',
            ]);

        $expectedReparationRequestMaterial = $reparationRequestMaterials->random();
        $expectedReparationRequestMaterial->name = 'Needle in title';
        $expectedReparationRequestMaterial->save();

        $endpointUri = $this->urlGenerator->route('api.v1.reparation-request-material.index', [
            'filter' => [
                'name' => 'Needle',
            ],
        ]);

        // When
        $response = $this->get($endpointUri);

        // Then
        $response->assertOk()
            ->assertJson(
                fn (AssertableJson $json) => $json
                    ->has('data', 1,
                        fn (AssertableJson $json) => $json
                            ->where('uuid', $expectedReparationRequestMaterial->uuid)
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
        ReparationRequestMaterial::factory(3)
            ->for(ReparationRequest::factory()
                ->for(User::factory(), 'reporter')
            )
            ->create();

        $endpointUri = $this->urlGenerator->route('api.v1.reparation-request-material.index', [
            'sort' => [
                $dateColumn,
            ],
        ]);

        // When
        $response = $this->get($endpointUri);

        // Then
        $response->assertOk();

        // Assert that the order of resources are according to the expected sort criteria
        self::assertEquals(
            ReparationRequestMaterial::orderBy($dateColumn)->pluck('uuid'),
            (new Collection($response->decodeResponseJson()['data']))
                ->map(function (array $resource) {
                    return $resource['uuid'];
                })
        );
    }

    public function testIndexEndpointIncludingMaterials(): void
    {
        // Given
        $reparationRequest = ReparationRequestMaterial::factory()
            ->for(ReparationRequest::factory()
                ->for(User::factory(), 'reporter')
            )
            ->create();

        $endpointUri = $this->urlGenerator->route('api.v1.reparation-request-material.index', [
            'include' => [
                'reparationRequest',
            ],
        ]);

        // When
        $response = $this->get($endpointUri);

        // Then
        $response->assertOk()
            ->assertJson(
                fn (AssertableJson $json) => $json
                    ->has('data', 1,
                        fn (AssertableJson $json) => $json
                            ->where('uuid', $reparationRequest->uuid)
                            ->has('reparation_request')
                            ->etc()
                    )
                    ->etc()
            );
    }

    public function testCreateEndpoint(): void
    {
        $reparationRequest = ReparationRequest::factory()
            ->for(User::factory(), 'reporter')
            ->create();

        $data = [
            'name'                  => 'test',
            'is_mandatory'          => false,
            'reparation_request_id' => $reparationRequest->id,
        ];

        $endpointUri = $this->urlGenerator->route('api.v1.reparation-request-material.store');

        $response = $this->post($endpointUri, $data, [
            'Accept' => 'application/json',
        ]);

        $response->assertCreated()
            ->assertJsonPath('data.reparation_request.uuid', $reparationRequest->uuid);
    }

    public function testCreateEndpointValidation(): void
    {
        $reparationRequest = ReparationRequest::factory()
            ->for(User::factory(), 'reporter')
            ->create();

        $data = [
            'name'                  => 'test',
            'is_mandatory'          => 'ttuurur',
            'reparation_request_id' => $reparationRequest->id,
        ];

        $endpointUri = $this->urlGenerator->route('api.v1.reparation-request-material.store');

        $response = $this->post($endpointUri, $data, [
            'Accept' => 'application/json',
        ]);

        $response->assertUnprocessable()
            ->assertJson(
                fn (AssertableJson $json) => $json
                    ->has('errors.is_mandatory')
                    ->etc()
            );
    }
}
