<?php

declare(strict_types=1);

namespace App\ACL\Roles;

use Illuminate\Support\Collection;

/**
 * This role has been implicitly granted all permissions by using the gate before hook. See the auth service provider.
 *
 * @codeCoverageIgnore
 */
final class AdminRole implements RoleInterface
{
    public function getName(): string
    {
        return 'admin';
    }

    public function getPermissions(): Collection
    {
        return new Collection();
    }
}
