<?php

namespace App\Models;

use App\Notifications\VerificationCodeNotification;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
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
        'verification_code',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function hotelReviews()
    {
        return $this->hasMany(HotelReview::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }


    public function sendVerificationCodeNotification($verificationCode)
    {
        $this->notify(new VerificationCodeNotification($verificationCode));
    }
}
