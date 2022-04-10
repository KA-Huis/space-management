<?php

declare(strict_types=1);

namespace Tests\Feature\Models;

use App\Models\AuthorizedUser;
use App\Models\Group;
use App\Models\GroupType;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

/**
 * @group Feature
 * @group Models
 */
class GroupTest extends TestCase
{
    use DatabaseTransactions;

    public function testItCanBelongToManyUsers(): void
    {
        // Given
        $group = Group::factory()
            ->for(GroupType::factory())
            ->has(AuthorizedUser::factory()->count(3), 'users')
            ->create();

        // Then
        self::assertEquals(3, $group->users()->count());
    }

    public function testItCanBelongToAGroupType(): void
    {
        // Given
        $group = Group::factory()
            ->for(GroupType::factory())
            ->create();

        // Then
        self::assertInstanceOf(GroupType::class, $group->groupType);
    }
}
