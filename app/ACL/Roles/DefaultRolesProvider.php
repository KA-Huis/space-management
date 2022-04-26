<?php

declare(strict_types=1);

namespace App\ACL\Roles;

use App\ACL\Contracts\RolesProvider;

class DefaultRolesProvider implements RolesProvider
{
    public function resolve(): RoleCollection
    {
        return new RoleCollection([
            new MemberRole(),
            new ConciergeRole(),
        ]);
    }
}
