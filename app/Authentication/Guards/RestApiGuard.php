<?php

declare(strict_types=1);

namespace App\Authentication\Guards;

class RestApiGuard implements GuardInterface
{
    public function getName(): string
    {
        return 'rest_api';
    }
}
