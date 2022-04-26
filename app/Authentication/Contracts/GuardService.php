<?php

declare(strict_types=1);

namespace App\Authentication\Contracts;

use Illuminate\Support\Collection;

interface GuardService
{
    public function getAll(): Collection;

    public function existsByName(string $guardName): bool;
}
