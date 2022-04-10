<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Concerns\HasUuid;
use App\Notifications\Auth\ResetPassword;
use Exception;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContact;
use Illuminate\Contracts\Routing\UrlGenerator;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Collection;
use Spatie\Permission\Traits\HasRoles;

class AuthorizedUser extends Authenticatable implements CanResetPasswordContact
{
    use HasFactory;
    use Notifiable;
    use SoftDeletes;
    use HasUuid;
    use HasRoles;
    use CanResetPassword;

    public const AUTHENTICATION_GUARD = 'authorized_users';
    public const AUTHENTICATION_PROVIDER = 'authorized_users';

    public const DATABASE_TABLE = 'authorized_users';

    /** @var string[] */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
    ];

    /** @var array */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /** @var array */
    protected $casts = [
        'uuid' => 'string',
        'email_verified_at' => 'datetime',
    ];

    public function groups(): BelongsToMany
    {
        return $this->belongsToMany(Group::class);
    }

    public function getFullName(): string
    {
        $nameParts = (new Collection([
            $this->first_name,
            $this->last_name,
        ]));

        return $nameParts
            ->filter(function ($part) {
                return is_string($part) && !empty($part);
            })
            ->map(function ($part) {
                return trim($part);
            })
            ->implode(' ');
    }

    /**
     * Send the email verification notification.
     *
     * @throws Exception
     */
    public function sendEmailVerificationNotification(): void
    {
        throw new Exception('This feature is not created yet.');
//        $this->notify(new VerifyEmail);
    }

    /**
     * Send the password reset notification.
     *
     * @param string $token
     *
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPassword(
            resolve(UrlGenerator::class),
            $token
        ));
    }
}
