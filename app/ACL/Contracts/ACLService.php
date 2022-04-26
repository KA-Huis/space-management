<?php

declare(strict_types=1);

namespace App\ACL\Contracts;

use App\Authentication\Guards\GuardInterface;
use Illuminate\Support\Collection;

interface ACLService
{
    public function synchroniseRolesAndPermissions(GuardInterface $guard): void;

    public function getRoles(): Collection;
}
