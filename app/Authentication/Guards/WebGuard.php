<?php

declare(strict_types=1);

namespace App\Authentication\Guards;

class WebGuard implements GuardInterface
{
    public function getName(): string
    {
        return 'web';
    }
}
