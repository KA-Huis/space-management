<?php

namespace App\Models;

use App\Models\Concerns\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Space extends Model
{
    use HasFactory;
    use SoftDeletes;
    use HasUuid;

    /** @var array */
    protected $casts = [
        'uuid' => 'string',
        'name' => 'string',
        'description' => 'string',
        'is_open_for_reservations' => 'boolean',
    ];
}
