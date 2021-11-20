<?php

namespace App\Models;

use App\Models\Concerns\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Group extends Model
{
    use HasFactory,
        SoftDeletes,
        HasUuid;

    /** @var array */
    protected $casts = [
        'uuid' => 'string',
        'name' => 'string',
    ];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }

    public function groupType(): BelongsTo
    {
        return $this->belongsTo(GroupType::class);
    }
}
