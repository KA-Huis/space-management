<?php

declare(strict_types=1);

namespace App\ACL\Roles;

use Illuminate\Support\Collection;

interface RoleInterface
{
    public function getName(): string;

    public function getPermissions(): Collection;
}
