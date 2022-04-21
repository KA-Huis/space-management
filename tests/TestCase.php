<?php

declare(strict_types=1);

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Testing\TestResponse;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function setUp(): void
    {
        parent::setUp();

        TestResponse::macro('assertJsonPaginated', function () {
            return $this->assertJson([
                'data'  => true,
                'links' => true,
                'meta'  => true,
            ]);
        });
    }
}
