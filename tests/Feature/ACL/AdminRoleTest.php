<?php

declare(strict_types=1);

namespace Tests\Feature\ACL;

use App\ACL\Roles\AdminRole;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Gate;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class AdminRoleTest extends TestCase
{
    use RefreshDatabase;

    public function testGateBeforeHookToImplicitlyGrantAdminRoleAllPermissions(): void
    {
        // Given
        Role::findOrCreate((new AdminRole())->getName());

        $user = User::factory()->create();
        $user->assignRole((new AdminRole())->getName());

        // When

        $response = Gate::forUser($user)->allows('something random that does not exists', User::class);

        // Then
        self::assertTrue($response);
    }
}
