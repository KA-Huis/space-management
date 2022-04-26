<?php

declare(strict_types=1);

namespace App\ACL\Contracts;

use Illuminate\Support\Collection;

interface ACLService
{
    public function synchroniseRolesAndPermissions(): void;

    public function getRoles(): Collection;
}
