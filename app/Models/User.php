<?php

namespace App\Models;

use App\Models\Concerns\HasUuid;
use App\Notifications\Auth\ResetPassword;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContact;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Contracts\Routing\UrlGenerator;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Collection;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements MustVerifyEmail, CanResetPasswordContact
{
    use HasApiTokens,
        HasFactory,
        Notifiable,
        HasUuid,
        HasRoles,
        CanResetPassword;

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
        'email_verified_at' => 'datetime',
    ];

    public function getFullName(): string
    {
        $nameParts = (new Collection([
            $this->first_name,
            $this->last_name
        ]));

        return $nameParts
            ->filter(function ($part) {
                return !empty($part);
            })
            ->implode(' ');
    }

    /**
     * Send the email verification notification.
     */
    public function sendEmailVerificationNotification(): void
    {
        throw new Exception('This feature is not created yet.');
//        $this->notify(new VerifyEmail);
    }

    /**
     * Send the password reset notification.
     *
     * @param  string  $token
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
