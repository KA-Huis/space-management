<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Concerns\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class ReparationRequestMaterial extends Model
{
    use HasFactory;
    use SoftDeletes;
    use HasUuid;

    protected $fillable = [
        'name',
        'is_mandatory',
    ];

    /** @var array */
    protected $casts = [
        'uuid' => 'string',
        'name' => 'string',
        'is_mandatory' => 'boolean',
    ];

    public function reparationRequest(): BelongsTo
    {
        return $this->belongsTo(ReparationRequest::class);
    }
}
