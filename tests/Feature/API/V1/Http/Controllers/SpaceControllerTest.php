<?php

declare(strict_types=1);

namespace Tests\Feature\API\V1\Http\Controllers;

use App\Authentication\Guards\RestApiGuard;
use App\Models\Space;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Routing\UrlGenerator;
use Illuminate\Support\Collection;
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

    public function testIndexEndpoint(): void
    {
        // Given
        $user = User::factory()->create();

        $spaces = Space::factory(3)
            ->create();

        $endpointUri = $this->urlGenerator->route('api.v1.space.index');

        // When
        $response = $this
            ->actingAs($user, (new RestApiGuard())->getName())
            ->get($endpointUri);

        // Then
        $firstSpace = $spaces->first();

        $response->assertOk()
            ->assertJsonPaginated()
            ->assertJson(
                fn(AssertableJson $json) => $json
                    ->has('data', 3,
                        fn(AssertableJson $json) => $json
                            ->where('id', $firstSpace->id)
                            ->etc()
                    )
                    ->etc()
            );
    }

    public function testIndexEndpointFilteringOnName(): void
    {
        // Given
        $user = User::factory()->create();

        $spaces = Space::factory(3)
            ->create([
                'name' => 'Unrelevant title',
            ]);
        $expectedSpace = $spaces->random();
        $expectedSpace->title = 'Needle in title';
        $expectedSpace->save();

        $endpointUri = $this->urlGenerator->route('api.v1.space.index', [
            'filter' => [
                'title' => 'Needle',
            ],
        ]);

        // When
        $response = $this
            ->actingAs($user, (new RestApiGuard())->getName())
            ->get($endpointUri);

        // Then
        $response->assertOk()
            ->assertJsonPaginated()
            ->assertJson(
                fn(AssertableJson $json) => $json
                    ->has('data', 1,
                        fn(AssertableJson $json) => $json
                            ->where('id', $expectedSpace->id)
                            ->etc()
                    )
                    ->etc()
            );
    }

    public function testIndexEndpointFilteringOnDescription(): void
    {
        // Given
        $user = User::factory()->create();

        $spaces = Space::factory(3)
            ->create([
                'description' => 'Unrelevant description',
            ]);
        $expectedSpace = $spaces->random();
        $expectedSpace->description = 'Needle in description';
        $expectedSpace->save();

        $endpointUri = $this->urlGenerator->route('api.v1.reparation-request.index', [
            'filter' => [
                'description' => 'Needle',
            ],
        ]);

        // When
        $response = $this
            ->actingAs($user, (new RestApiGuard())->getName())
            ->get($endpointUri);

        // Then
        $response->assertOk()
            ->assertJsonPaginated()
            ->assertJson(
                fn(AssertableJson $json) => $json
                    ->has('data', 1,
                        fn(AssertableJson $json) => $json
                            ->where('id', $expectedSpace->id)
                            ->etc()
                    )
                    ->etc()
            );
    }

    public function testIndexEndpointFilteringOnIsOpenForReservations(): void
    {
        // Given
        $user = User::factory()->create();

        $spaces = Space::factory(3)
            ->create([
                'is_open_for_reservations' => true,
            ]);
        $expectedSpace = $spaces->random();
        $expectedSpace->is_open_for_reservations = false;
        $expectedSpace->save();

        $endpointUri = $this->urlGenerator->route('api.v1.space.index', [
            'filter' => [
                'is_open_for_reservations' => false,
            ],
        ]);

        // When
        $response = $this
            ->actingAs($user, (new RestApiGuard())->getName())
            ->get($endpointUri);

        // Then
        $response->assertOk()
            ->assertJsonPaginated()
            ->assertJson(
                fn(AssertableJson $json) => $json
                    ->has('data', 1,
                        fn(AssertableJson $json) => $json
                            ->where('id', $expectedSpace->id)
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

        Space::factory(3)->create();

        $endpointUri = $this->urlGenerator->route('api.v1.space.index', [
            'sort' => [
                $dateColumn,
            ],
        ]);

        // When
        $response = $this
            ->actingAs($user, (new RestApiGuard())->getName())
            ->get($endpointUri);

        // Then
        $response->assertOk()
            ->assertJsonPaginated();

        // Assert that the order of resources are according to the expected sort criteria
        self::assertEquals(
            Space::orderBy($dateColumn)->pluck('id'),
            (new Collection($response->decodeResponseJson()['data']))
                ->map(function (array $resource) {
                    return $resource['id'];
                })
        );
    }
}
