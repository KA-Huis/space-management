<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Concerns\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class ReparationRequest extends Model
{
    use HasFactory;
    use SoftDeletes;
    use HasUuid;

    /** @var array */
    protected $guarded = [];

    /** @var array */
    protected $casts = [
        'uuid'        => 'string',
        'title'       => 'string',
        'description' => 'string',
        'priority'    => 'integer',
    ];

    public function reporter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reporter_id');
    }

    public function currentStatus(): HasOne
    {
        return $this->hasOne(ReparationRequestStatus::class)->latest();
    }

    public function statuses(): HasMany
    {
        return $this->hasMany(ReparationRequestStatus::class);
    }

    public function materials(): HasMany
    {
        return $this->hasMany(ReparationRequestMaterial::class);
    }
}
