<?php

declare(strict_types=1);

namespace App\ACL\Roles\Providers;

use App\ACL\Contracts\RolesProvider;
use App\ACL\Roles\ConciergeRole;
use App\ACL\Roles\MemberRole;
use App\ACL\Roles\RoleCollection;

/**
 * @codeCoverageIgnore
 */
final class DefaultRolesProvider implements RolesProvider
{
    public function resolve(): RoleCollection
    {
        return new RoleCollection([
            new MemberRole(),
            new ConciergeRole(),
        ]);
    }
}
