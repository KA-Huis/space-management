<?php

declare(strict_types=1);

namespace Tests\Feature\API\V1\Http\Resources;

use App\API\V1\Http\Resources\GroupTypeResource;
use App\Models\Group;
use App\Models\GroupType;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Mockery;
use Tests\TestCase;

class GroupTypeResourceTest extends TestCase
{
    public function testDefaultResponse(): void
    {
        // Given
        $groupType = GroupType::factory()
            ->has(Group::factory())
            ->create();
        $groupResource = new GroupTypeResource($groupType);
        $request = Mockery::mock(Request::class);

        // When
        $response = $groupResource->toResponse($request);

        // Then
        $castedGroupType = $groupType->toArray();

        self::assertEquals(
            [
                'id'          => Arr::get($castedGroupType, 'id'),
                'name'        => Arr::get($castedGroupType, 'name'),
                'created_at'  => Arr::get($castedGroupType, 'created_at'),
                'updated_at'  => Arr::get($castedGroupType, 'updated_at'),
                'deleted_at'  => Arr::get($castedGroupType, 'deleted_at'),
            ],
            Arr::get((array) $response->getData(true), 'data')
        );
    }

    public function testOptionalIncludedGroups(): void
    {
        // Given
        $groupType = GroupType::factory()
            ->has(Group::factory()->count(3))
            ->create();
        $groupTypeResource = new GroupTypeResource($groupType);
        $request = Mockery::mock(Request::class);

        // When
        $response = $groupTypeResource->toArray($request);

        // Then
        $this->assertArrayHasKey('groups', $response);
    }
}
