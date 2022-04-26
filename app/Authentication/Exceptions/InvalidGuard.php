<?php

declare(strict_types=1);

namespace App\Authentication\Exceptions;

use Exception;

class InvalidGuard extends Exception
{
    public static function withName(string $guardName): self
    {
        return new static(sprintf('Invalid guard `%s` provided.', $guardName));
    }
}
