<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Concerns\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class ApiUser extends Authenticatable
{
    use HasFactory;
    use Notifiable;
    use SoftDeletes;
    use HasUuid;
    use HasRoles;

    public const AUTHENTICATION_GUARD = 'api_users';
    public const AUTHENTICATION_PROVIDER = 'api_users';

    /** @var string[] */
    protected $fillable = [
        'email',
        'password',
    ];

    /** @var array */
    protected $hidden = [
        'password',
    ];

    /** @var array */
    protected $casts = [
        'uuid' => 'string',
    ];
}
