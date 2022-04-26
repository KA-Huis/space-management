<?php

declare(strict_types=1);

namespace App\ACL;

use App\ACL\Contracts\ACLService as ACLServiceContract;
use App\ACL\Contracts\RolesProvider;
use App\ACL\Roles\RoleCollection;
use App\ACL\Roles\RoleInterface;
use App\Authentication\Guards\GuardInterface;
use App\Authentication\Guards\RestApiGuard;
use App\Authentication\Guards\WebGuard;
use Illuminate\Support\Collection;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class ACLService implements ACLServiceContract
{
    private RolesProvider $rolesProvider;

    public function __construct(RolesProvider $rolesProvider)
    {
        $this->rolesProvider = $rolesProvider;
    }

    public function synchroniseRolesAndPermissions(): void
    {
        $guards = new Collection([
            new WebGuard(),
            new RestApiGuard(),
        ]);

        $this->getRoles()
            ->each(function (RoleInterface $role) use ($guards) {
                $guards->each(function (GuardInterface $guard) use ($role) {
                    Role::findOrCreate($role->getName(), $guard->getName())
                        ->syncPermissions(
                            $role->getPermissions()->map(function (string $permission) use ($guard) {
                                return Permission::findOrCreate($permission, $guard->getName());
                            })
                        );
                });
            });
    }

    public function getRoles(): RoleCollection
    {
        return $this->rolesProvider->resolve();
    }
}
