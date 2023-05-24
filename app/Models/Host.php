<?php

namespace App\Models;

use App\Notifications\ResetPasswordNotification;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Sanctum\HasApiTokens;


class Host extends Authenticatable implements MustVerifyEmail
{
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone_number',
        'gender',
        'image',
        'password',
        'IsItAccepted',
        'birthdate',
        'hotel_id'
    ];

    use HasApiTokens, HasFactory, Notifiable, TwoFactorAuthenticatable;

    public function hotel()
    {
        return $this->belongsTo(Hotel::class);
    }

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function sendPasswordResetNotification($token)
    {

        $url = 'http://127.0.0.1:8000/api/host/reset-password?token=' . $token;

        $this->notify(new ResetPasswordNotification($url));
    }

}
