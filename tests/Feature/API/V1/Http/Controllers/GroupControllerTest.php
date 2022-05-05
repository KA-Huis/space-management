<?php

declare(strict_types=1);

namespace Tests\Feature\API\V1\Http\Controllers;

use App\Authentication\Guards\RestApiGuard;
use App\Models\Group;
use App\Models\GroupType;
use App\Models\ReparationRequest;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Routing\UrlGenerator;
use Illuminate\Support\Collection;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class GroupControllerTest extends TestCase
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

        $groups = Group::factory(3)
            ->for(GroupType::factory())
            ->create();

        $endpointUri = $this->urlGenerator->route('api.v1.group.index');

        // When
        $response = $this
            ->actingAs($user, (new RestApiGuard())->getName())
            ->get($endpointUri);

        // Then
        $firstGroup = $groups->first();

        $response->assertOk()
            ->assertJsonPaginated()
            ->assertJson(
                fn (AssertableJson $json) => $json
                    ->has('data', 3,
                        fn (AssertableJson $json) => $json
                            ->where('id', $firstGroup->id)
                            ->etc()
                    )
                    ->etc()
            );
    }

    public function testIndexEndpointFilteringOnName(): void
    {
        // Given
        $user = User::factory()->create();

        $groups = Group::factory(3)
            ->for(GroupType::factory())
            ->create([
                'name' => 'Unrelevant name',
            ]);
        $expectedGroup = $groups->random();
        $expectedGroup->name = 'Needle in name';
        $expectedGroup->save();

        $endpointUri = $this->urlGenerator->route('api.v1.group.index', [
            'filter' => [
                'name' => 'needle',
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
                            ->where('id', $expectedGroup->id)
                            ->etc()
                    )
                    ->etc()
            );
    }

    public function testIndexEndpointFilteringOnGroupTypeId(): void
    {
        // Given
        $user = User::factory()->create();

        $groups = Group::factory(3)
            ->for(GroupType::factory())
            ->create();
        $expectedGroup = $groups->random();
        $expectedGroup->group_type_id = GroupType::factory()->create()->id;
        $expectedGroup->save();

        $endpointUri = $this->urlGenerator->route('api.v1.group.index', [
            'filter' => [
                'group_type_id' => $expectedGroup->group_type_id,
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
                            ->where('id', $expectedGroup->id)
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

        Group::factory(3)
            ->for(GroupType::factory())
            ->create();

        $endpointUri = $this->urlGenerator->route('api.v1.group.index', [
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
            Group::orderBy($dateColumn)->pluck('id'),
            (new Collection($response->decodeResponseJson()['data']))
                ->map(function (array $resource) {
                    return $resource['id'];
                })
        );
    }

    public function testIndexEndpointIncludingReporter(): void
    {
        $this->withoutExceptionHandling();
        // Given
        $user = User::factory()->create();

        $group = Group::factory()
            ->for(GroupType::factory())
            ->create();

        $endpointUri = $this->urlGenerator->route('api.v1.group.index', [
            'include' => [
                'groupType',
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
                            ->where('id', $group->id)
                            ->has('group_type')
                            ->etc()
                    )
                    ->etc()
            );
    }

    public function testShowEndpoint(): void
    {
        // Given
        $user = User::factory()->create();

        $group = Group::factory()
            ->for(GroupType::factory())
            ->create();

        $endpointUri = $this->urlGenerator->route('api.v1.group.show', [
            'group' => $group->id,
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
                            ->where('id', $group->id)
                            ->etc()
                    )
                    ->etc()
            );
    }

    public function testDestroyEndpoint(): void
    {
        // Given
        $user = User::factory()->create();

        $groups = Group::factory(3)
            ->for(GroupType::factory())
            ->create();
        /** @var ReparationRequest $firstGroup */
        $firstGroup = $groups->first();

        $endpointUri = $this->urlGenerator->route('api.v1.group.destroy', [
            'group' => $firstGroup->id,
        ]);

        // When
        $response = $this
            ->actingAs($user, (new RestApiGuard())->getName())
            ->delete($endpointUri);

        // Then
        self::assertFalse($firstGroup->trashed());

        $response->assertOk();
    }

    public function testStoreEndpoint(): void
    {
        // Given
        $user = User::factory()->create();
        $groupType = GroupType::factory()->create();

        $data = [
            'name'                 => 'Some name',
            'group_type_id'                 => $groupType->id,
        ];

        $endpointUri = $this->urlGenerator->route('api.v1.group.store');

        // When
        $response = $this
            ->actingAs($user, (new RestApiGuard())->getName())
            ->post($endpointUri, $data);

        // Then
        $group = Group::latest()->first();

        $response->assertCreated()
            ->assertJsonPath('data.id', $group->id);
    }

    public function testStoreEndpointValidation(): void
    {
        // Given
        $user = User::factory()->create();

        $data = [
            'group_type_id'              => 0,
        ];

        $endpointUri = $this->urlGenerator->route('api.v1.group.store');

        // When
        $response = $this
            ->actingAs($user, (new RestApiGuard())->getName())
            ->post($endpointUri, $data);

        // Then
        $response->assertRedirect()
            ->assertSessionHasErrors([
                'name',
                'group_type_id',
            ]);
    }

    public function testUpdateEndpoint(): void
    {
        // Given
        $user = User::factory()->create();
        $groupType = GroupType::factory()->create();
        $group = Group::create([
            'name'                 => 'Some name',
            'group_type_id'           => $groupType->id,
        ]);

        $newGroupType = GroupType::factory()->create();
        $newData = [
            'name'                 => 'Some name',
            'group_type_id'           => $newGroupType->id,
        ];

        $endpointUri = $this->urlGenerator->route('api.v1.group.update', [
            'group' => $group->id,
        ]);

        // When
        $response = $this
            ->actingAs($user, (new RestApiGuard())->getName())
            ->put($endpointUri, $newData);

        // Then
        $group->refresh();

        $response->assertSuccessful()
            ->assertJsonPath('data.id', $group->id);

        self::assertEquals($group->name, $newData['name']);
        self::assertEquals($group->group_type_id, $newData['group_type_id']);
    }

    public function testUpdateEndpointValidation(): void
    {
        // Given
        $user = User::factory()->create();
        $groupType = GroupType::factory()->create();
        $group = Group::create([
            'name'                 => 'Some name',
            'group_type_id'           => $groupType->id,
        ]);

        $newData = [
            'name'           => 'Edited name',
            'priority'              => 0,
        ];

        $endpointUri = $this->urlGenerator->route('api.v1.group.update', [
            'group' => $group->id,
        ]);

        // When
        $response = $this
            ->actingAs($user, (new RestApiGuard())->getName())
            ->put($endpointUri, $newData);

        // Then
        $response->assertRedirect()
            ->assertSessionHasErrors([
                'group_type_id',
            ]);
    }
}
