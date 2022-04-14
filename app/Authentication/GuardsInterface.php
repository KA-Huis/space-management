<?php

declare(strict_types=1);

namespace App\Authentication;

interface GuardsInterface
{
    public const WEB = 'web';
    public const REST_API = 'rest_api';
}
