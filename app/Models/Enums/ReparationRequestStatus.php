<?php

namespace App\Models\Enums;

class ReparationRequestStatus
{
    public const STATUS_OPEN = 10;
    public const STATUS_IN_PROGRESS = 20;
    public const STATUS_IN_WAITING = 30;
    public const STATUS_IN_DONE = 40;
    public const STATUS_IN_WONT_DO = 50;

    public const ALL_STATUSES = [
        self::STATUS_OPEN,
        self::STATUS_IN_PROGRESS,
        self::STATUS_IN_WAITING,
        self::STATUS_IN_DONE,
        self::STATUS_IN_WONT_DO,
    ];
}
