<?php

namespace Tests\Feature\Models;

use App\Models\Group;
use App\Models\GroupType;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

/**
 * @group Feature
 * @group Models
 */
class GroupTypeTest extends TestCase
{
    use DatabaseTransactions;

    public function testItCanHaveManyGroups(): void
    {
        // Given
        $groupType = GroupType::factory()
            ->has(Group::factory()->count(3))
            ->create();

        // Then
        self::assertEquals(3, $groupType->groups()->count());
    }
}
