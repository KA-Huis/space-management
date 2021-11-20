<?php

namespace Tests\Feature\Models;

use App\Models\Group;
use App\Models\GroupType;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

/**
 * @group Feature
 * @group Models
 */
class GroupTest extends TestCase
{
    use DatabaseTransactions;

    public function test_it_can_belong_to_many_users(): void
    {
        // Given
        $group = Group::factory()
            ->for(GroupType::factory())
            ->has(User::factory()->count(3))
            ->create();

        // Then
        self::assertEquals(3, $group->users()->count());
    }

    public function test_it_can_belong_to_a_group_type(): void
    {
        // Given
        $group = Group::factory()
            ->for(GroupType::factory())
            ->create();

        // Then
        self::assertInstanceOf(GroupType::class, $group->groupType);
    }
}
