<?php

declare(strict_types=1);

namespace App\ACL;

use App\ACL\Contracts\ACLService as ACLServiceContract;
use App\ACL\Contracts\RolesProvider;
use App\ACL\Roles\RoleCollection;
use App\ACL\Roles\RoleInterface;
use App\Authentication\Guards\GuardInterface;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class ACLService implements ACLServiceContract
{
    private RolesProvider $rolesProvider;

    public function __construct(RolesProvider $rolesProvider)
    {
        $this->rolesProvider = $rolesProvider;
    }

    public function synchroniseRolesAndPermissions(GuardInterface $guard): void
    {
        $this->getRoles()
            ->each(function (RoleInterface $role) use ($guard) {
                Role::findOrCreate($role->getName(), $guard->getName())
                    ->syncPermissions(
                        $role->getPermissions()->map(function (string $permission) use ($guard) {
                            return Permission::findOrCreate($permission, $guard->getName());
                        })
                    );
            });
    }

    public function getRoles(): RoleCollection
    {
        return $this->rolesProvider->resolve();
    }
}
