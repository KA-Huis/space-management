<?php

namespace App\Models\Enums;

class ReparationRequestPriority
{
    public const PRIORITY_LOW = 100;
    public const PRIORITY_MEDIUM = 200;
    public const PRIORITY_HIGH = 300;
    public const PRIORITY_HIGHEST = 400;

    public const ALL_PRIORITIES = [
        self::PRIORITY_LOW,
        self::PRIORITY_MEDIUM,
        self::PRIORITY_HIGH,
        self::PRIORITY_HIGHEST,
    ];
}
