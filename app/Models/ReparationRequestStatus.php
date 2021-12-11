<?php

namespace App\Models;

use App\Models\Concerns\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReparationRequestStatus extends Model
{
    use HasFactory;
    use HasUuid;

    /** @var array */
    protected $casts = [
        'uuid' => 'string',
        'status' => 'integer',
    ];

    public function reparationRequest(): BelongsTo
    {
        return $this->belongsTo(ReparationRequest::class);
    }
}
