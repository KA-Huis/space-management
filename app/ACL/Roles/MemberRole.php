<?php

declare(strict_types=1);

namespace App\ACL\Roles;

use Illuminate\Support\Collection;

/**
 * @codeCoverageIgnore
 */
final class MemberRole implements RoleInterface
{
    public function getName(): string
    {
        return 'member';
    }

    public function getPermissions(): Collection
    {
        return new Collection([
        ]);
    }
}
