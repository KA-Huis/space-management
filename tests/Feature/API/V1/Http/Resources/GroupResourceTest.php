<?php

declare(strict_types=1);

namespace Tests\Feature\API\V1\Http\Resources;

use App\API\V1\Http\Resources\GroupResource;
use App\Models\Group;
use App\Models\GroupType;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Mockery;
use Tests\TestCase;

class GroupResourceTest extends TestCase
{
    public function testDefaultResponse(): void
    {
        // Given
        $group = Group::factory()
            ->for(GroupType::factory())
            ->create();
        $groupResource = new GroupResource($group);
        $request = Mockery::mock(Request::class);

        // When
        $response = $groupResource->toResponse($request);

        // Then
        $castedGroup = $group->toArray();

        self::assertEquals(
            [
                'id'          => Arr::get($castedGroup, 'id'),
                'name'        => Arr::get($castedGroup, 'name'),
                'created_at'  => Arr::get($castedGroup, 'created_at'),
                'updated_at'  => Arr::get($castedGroup, 'updated_at'),
                'deleted_at'  => Arr::get($castedGroup, 'deleted_at'),
            ],
            Arr::get((array) $response->getData(true), 'data')
        );
    }

    public function testOptionalIncludedGroupType(): void
    {
        // Given
        $group = Group::factory()
            ->for(GroupType::factory())
            ->create();
        $groupResource = new GroupResource($group);
        $request = Mockery::mock(Request::class);

        // When
        $response = $groupResource->toArray($request);

        // Then
        $this->assertArrayHasKey('group_type', $response);
    }
}
