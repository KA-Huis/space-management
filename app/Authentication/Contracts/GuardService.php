<?php

declare(strict_types=1);

namespace App\Authentication\Contracts;

use App\Authentication\Guards\GuardInterface;
use Illuminate\Support\Collection;

interface GuardService
{
    public function getAll(): Collection;

    public function getByName(string $guardName): GuardInterface;

    public function existsByName(string $guardName): bool;
}
