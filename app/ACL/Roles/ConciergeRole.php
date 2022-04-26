<?php

declare(strict_types=1);

namespace App\ACL\Roles;

use Illuminate\Support\Collection;

class ConciergeRole implements RoleInterface
{
    public function getName(): string
    {
        return 'concierge';
    }

    public function getPermissions(): Collection
    {
        return new Collection([
        ]);
    }
}
