<?php

declare(strict_types=1);

namespace Tests\Feature\Console\Commands\ACL;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Symfony\Component\Console\Command\Command;
use Tests\TestCase;

class SynchroniseRolesAndPermissions extends TestCase
{
    use RefreshDatabase;

    public function testItCanSynchroniseRolesAndPermissions(): void
    {
        // When
        $this->artisan('system:acl:synchronise-roles-and-permissions')
            ->assertExitCode(Command::SUCCESS);
    }

    public function testItThrowsErrorWhenGuardIsInvalid(): void
    {
        // When
        $this
            ->artisan('system:acl:synchronise-roles-and-permissions', [
                'guard' => 'adsfadsfafsdd',
            ])
            ->assertExitCode(Command::FAILURE);
    }
}
