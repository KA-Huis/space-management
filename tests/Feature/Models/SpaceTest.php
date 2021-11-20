<?php

namespace Tests\Feature\Models;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

/**
 * @group Feature
 * @group Models
 */
class SpaceTest extends TestCase
{
    use DatabaseTransactions;

    public function test_example(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }
}
