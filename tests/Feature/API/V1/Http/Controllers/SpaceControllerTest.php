<?php

declare(strict_types=1);

namespace Tests\Feature\API\V1\Http\Controllers;

use App\Authentication\Guards\RestApiGuard;
use App\Models\ReparationRequest;
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
                fn (AssertableJson $json) => $json
                    ->has('data', 3,
                        fn (AssertableJson $json) => $json
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
                'name' => 'Unrelevant name',
            ]);
        $expectedSpace = $spaces->random();
        $expectedSpace->name = 'Needle in name';
        $expectedSpace->save();

        $endpointUri = $this->urlGenerator->route('api.v1.space.index', [
            'filter' => [
                'name' => 'Needle',
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
                fn (AssertableJson $json) => $json
                    ->has('data', 1,
                        fn (AssertableJson $json) => $json
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

        $endpointUri = $this->urlGenerator->route('api.v1.space.index', [
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
                fn (AssertableJson $json) => $json
                    ->has('data', 1,
                        fn (AssertableJson $json) => $json
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
                fn (AssertableJson $json) => $json
                    ->has('data', 1,
                        fn (AssertableJson $json) => $json
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

    public function testUpdateEndpoint(): void
    {
        // Given
        $user = User::factory()->create();
        $space = Space::create([
            'name'                     => 'Some name',
            'description'              => 'A description that is does not add anything of value.',
            'is_open_for_reservations' => true,
        ]);

        $newData = [
            'name'                     => 'New name',
            'description'              => 'Edited description',
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

        self::assertEquals($space->name, $newData['name']);
        self::assertEquals($space->description, $newData['description']);
        self::assertEquals($space->priority, $newData['is_open_for_reservations']);
    }

    public function testUpdateEndpointValidation(): void
    {
        // Given
        $user = User::factory()->create();
        $space = Space::create([
            'name'                     => 'Some name',
            'description'              => 'A description that is does not add anything of value.',
            'is_open_for_reservations' => true,
        ]);

        $newData = [
            'description'              => 'Edited description',
            'is_open_for_reservations' => null,
        ];

        $endpointUri = $this->urlGenerator->route('api.v1.space.update', [
            'space' => $space->id,
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
