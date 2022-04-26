<?php

declare(strict_types=1);

namespace App\ACL\Contracts;

use App\ACL\Roles\RoleCollection;

interface RolesProvider
{
    public function resolve(): RoleCollection;
}
