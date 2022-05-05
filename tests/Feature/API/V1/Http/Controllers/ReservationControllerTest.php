<?php

declare(strict_types=1);

namespace Tests\Feature\API\V1\Http\Controllers;

use App\Authentication\Guards\RestApiGuard;
use App\Models\Group;
use App\Models\GroupType;
use App\Models\Reservation;
use App\Models\Space;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Routing\UrlGenerator;
use Illuminate\Support\Collection;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class ReservationControllerTest extends TestCase
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

        $reservations = Reservation::factory(3)
            ->for(User::factory(), 'createdByUser')
            ->for(Space::factory())
            ->create();

        $endpointUri = $this->urlGenerator->route('api.v1.reservation.index');

        // When
        $response = $this
            ->actingAs($user, (new RestApiGuard())->getName())
            ->get($endpointUri);

        // Then
        $firstReservation = $reservations->first();

        $response->assertOk()
            ->assertJsonPaginated()
            ->assertJson(
                fn (AssertableJson $json) => $json
                    ->has('data', 3,
                        fn (AssertableJson $json) => $json
                            ->where('id', $firstReservation->id)
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

        Reservation::factory(3)
            ->for(User::factory(), 'createdByUser')
            ->for(Space::factory())
            ->create();

        $endpointUri = $this->urlGenerator->route('api.v1.reservation.index', [
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
            Reservation::orderBy($dateColumn)->pluck('id'),
            (new Collection($response->decodeResponseJson()['data']))
                ->map(function (array $resource) {
                    return $resource['id'];
                })
        );
    }

    public function testShowEndpoint(): void
    {
        // Given
        $user = User::factory()
            ->create();

        $reservation = Reservation::factory()
            ->for(User::factory(), 'createdByUser')
            ->for(Space::factory())
            ->create();

        $endpointUri = $this->urlGenerator->route('api.v1.reservation.show', [
            'reservation' => $reservation->id,
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
                            ->where('id', $reservation->id)
                            ->etc()
                    )
                    ->etc()
            );
    }

    public function testStoreEndpoint(): void
    {
        // Given
        $user = User::factory()->create();
        $space = Space::factory()->create();
        $group = Group::factory()
            ->for(GroupType::factory())
            ->create();

        $data = [
            'starts_at' => (string) Carbon::today(),
            'ends_at'   => (string) Carbon::today()->addDays(3),
            'space_id'  => $space->id,
            'group_id'  => $group->id,
        ];

        $endpointUri = $this->urlGenerator->route('api.v1.reservation.store');

        // When
        $response = $this
            ->actingAs($user, (new RestApiGuard())->getName())
            ->post($endpointUri, $data);

        // Then
        $reservation = Reservation::latest()->first();

        $response->assertCreated()
            ->assertJsonPath('data.id', $reservation->id);
    }

    public function testStoreEndpointValidation(): void
    {
        // Given
        $user = User::factory()->create();
        $data = [
            'starts_at' => (string) Carbon::today(),
            'ends_at'   => (string) Carbon::today()->addDays(3),
            'group_id'  => 0,
        ];

        $endpointUri = $this->urlGenerator->route('api.v1.reservation.store');

        // When
        $response = $this
            ->actingAs($user, (new RestApiGuard())->getName())
            ->post($endpointUri, $data);

        // Then
        $response->assertRedirect()
            ->assertSessionHasErrors([
                'space_id',
                'group_id',
            ]);
    }

    public function testUpdateEndpoint(): void
    {
        // Given
        $user = User::factory()->create();
        $space = Space::factory()->create();
        $group = Group::factory()
            ->for(GroupType::factory())
            ->create();

        $reservation = Reservation::create([
            'starts_at'          => Carbon::today(),
            'ends_at'            => Carbon::today()->addDays(3),
            'created_by_user_id' => $user->id,
            'space_id'           => $space->id,
            'group_id'           => $group->id,
        ]);

        $newGroup = Group::factory()
            ->for(GroupType::factory())
            ->create();

        $newData = [
            'starts_at'          => (string) Carbon::today()->addDay(),
            'ends_at'            => (string) Carbon::today()->addDays(3),
            'created_by_user_id' => $user->id,
            'space_id'           => $space->id,
            'group_id'           => $newGroup->id,
        ];

        $endpointUri = $this->urlGenerator->route('api.v1.reservation.update', [
            'reservation' => $reservation->id,
        ]);

        // When
        $response = $this
            ->actingAs($user, (new RestApiGuard())->getName())
            ->put($endpointUri, $newData);

        // Then
        $reservation->refresh();

        $response->assertSuccessful()
            ->assertJsonPath('data.id', $reservation->id);

        self::assertEquals($reservation->starts_at, $newData['starts_at']);
        self::assertEquals($reservation->group_id, $newData['group_id']);
    }

    public function testUpdateEndpointValidation(): void
    {
        // Given
        $user = User::factory()->create();
        $space = Space::factory()->create();
        $group = Group::factory()
            ->for(GroupType::factory())
            ->create();

        $reservation = Reservation::create([
            'starts_at'          => Carbon::today(),
            'ends_at'            => Carbon::today()->addDays(3),
            'created_by_user_id' => $user->id,
            'space_id'           => $space->id,
            'group_id'           => $group->id,
        ]);

        $newData = [
            'ends_at' => Carbon::today()->subDays(3),
        ];

        $newGroup = Group::factory()
            ->for(GroupType::factory())
            ->create();

        $endpointUri = $this->urlGenerator->route('api.v1.reservation.update', [
            'reservation' => $reservation->id,
            'group_id'    => $newGroup->id,
        ]);

        // When
        $response = $this
            ->actingAs($user, (new RestApiGuard())->getName())
            ->put($endpointUri, $newData);

        // Then
        $response->assertRedirect()
            ->assertSessionHasErrors([
                'starts_at',
                'ends_at',
                'created_by_user_id',
                'space_id',
            ]);
    }

    public function testDestroyEndpoint(): void
    {
        // Given
        $user = User::factory()->create();

        $reservation = Reservation::factory()
            ->for(User::factory(), 'createdByUser')
            ->for(Space::factory())
            ->create();

        $endpointUri = $this->urlGenerator->route('api.v1.reservation.destroy', [
            'reservation' => $reservation->id,
        ]);

        // When
        $response = $this
            ->actingAs($user, (new RestApiGuard())->getName())
            ->delete($endpointUri);

        // Then
        self::assertFalse($reservation->trashed());

        $response->assertOk();
    }
}
