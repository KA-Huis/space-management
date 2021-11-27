<?php

namespace App\Models;

use App\Models\Concerns\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Reservation extends Model
{
    use HasFactory,
        HasUuid,
        SoftDeletes;

    /** @var array */
    protected $casts = [
        'uuid' => 'string',
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
    ];

    public function createdByUser(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function reservationParticipants(): BelongsToMany
    {
        return $this->belongsToMany(
            User::class,
            'reservation_participant',
            'reservation_id',
            'user_id'
        );
    }

    public function space(): BelongsTo
    {
        return $this->belongsTo(Space::class);
    }
}