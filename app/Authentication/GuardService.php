<?php

declare(strict_types=1);

namespace App\Authentication;

use App\Authentication\Contracts\GuardService as GuardServiceContract;
use App\Authentication\Exceptions\InvalidGuard;
use App\Authentication\Guards\GuardInterface;
use App\Authentication\Guards\RestApiGuard;
use App\Authentication\Guards\WebGuard;
use Illuminate\Support\Collection;

class GuardService implements GuardServiceContract
{
    public function getAll(): Collection
    {
        return new Collection([
            new WebGuard(),
            new RestApiGuard(),
        ]);
    }

    /**
     * @throws InvalidGuard
     */
    public function getByName(string $guardName): GuardInterface
    {
        $guard = $this->getAll()->firstWhere(function (GuardInterface $guard) use ($guardName) {
            return $guard->getName() === $guardName;
        });

        if (!$guard instanceof GuardInterface) {
            throw InvalidGuard::withName($guardName);
        }

        return $guard;
    }

    public function existsByName(string $guardName): bool
    {
        return $this->getAll()->contains(function (GuardInterface $guard) use ($guardName) {
            return $guard->getName() === $guardName;
        });
    }
}
