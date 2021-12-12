<?php

namespace App\Models;

use App\Models\Concerns\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class GroupType extends Model
{
    use HasFactory;
    use SoftDeletes;
    use HasUuid;

    /** @var array */
    protected $casts = [
        'uuid' => 'string',
        'name' => 'string',
    ];

    public function groups(): HasMany
    {
        return $this->hasMany(Group::class);
    }
}
