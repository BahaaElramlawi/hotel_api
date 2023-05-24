<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Notifications\ResetPasswordNotification;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Laravel\Sanctum\HasApiTokens;
use Laravel\Fortify\TwoFactorAuthenticatable;

class User extends Authenticatable implements MustVerifyEmail
{
    protected $fillable = [
        'first_name',
        'last_name',
        'birthdate',
        'email',
        'phone_number',
        'gender',
        'image',
        'password',
    ];

    use HasApiTokens, HasFactory, Notifiable, TwoFactorAuthenticatable;

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function sendPasswordResetNotification($token)
    {
        $url = 'http://127.0.0.1:8000/api/reset-password?token=' . $token;
        $this->notify(new ResetPasswordNotification($url));
    }
}
