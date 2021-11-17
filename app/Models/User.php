<?php

namespace App\Models;

use App\Models\Concerns\HasUuid;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Collection;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens,
        HasFactory,
        Notifiable,
        HasUuid,
        HasRoles;

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

        dump($nameParts->toArray());

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
}
