<?php

declare(strict_types=1);

namespace App\Authentication\Guards;

interface GuardInterface
{
    public function getName(): string;
}
