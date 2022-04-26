<?php

declare(strict_types=1);

namespace App\ACL;

use App\ACL\Contracts\ACLService as ACLServiceContract;
use App\ACL\Roles\MemberRole;
use App\ACL\Roles\RoleInterface;
use App\Authentication\Guards\GuardInterface;
use Illuminate\Support\Collection;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class ACLService implements ACLServiceContract
{
    public function synchroniseRolesAndPermissions(GuardInterface $guard): void
    {
        $this->getRoles()
            ->each(function (RoleInterface $role) use ($guard) {
                Role::findOrCreate($role->getName(), $guard->getName())
                    ->syncPermissions(
                        $role->getPermissions()->map(function (string $permission) use ($guard) {
                            Permission::findOrCreate($permission, $guard->getName());
                        })
                    );
            });
    }

    public function getRoles(): Collection
    {
        return new Collection([
            new MemberRole(),
        ]);
    }
}
