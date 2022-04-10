<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Concerns\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Group extends Model
{
    use HasFactory;
    use SoftDeletes;
    use HasUuid;

    /** @var array */
    protected $casts = [
        'uuid' => 'string',
        'name' => 'string',
    ];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(AuthorizedUser::class, 'group_user', 'group_id', 'user_id');
    }

    public function groupType(): BelongsTo
    {
        return $this->belongsTo(GroupType::class);
    }
}
