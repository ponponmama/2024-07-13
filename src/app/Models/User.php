<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Auth\Notifications\VerifyEmail;


class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

    const ROLE_ADMIN = 1;
    const ROLE_SHOP_MANAGER = 2;
    const ROLE_USER = 3;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_name',
        'email',
        'password',
        'role',
        'email_verified_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Send the email verification notification.
     */

    public function sendEmailVerificationNotification()
    {
        $this->notify(new \App\Notifications\CustomVerifyEmail);
    }

    public function isShopManager()
    {
        return $this->role === 2;
    }

    public function isAdmin()
    {
        return $this->role === 1;
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

    public function favorites()
    {
        return $this->belongsToMany(Shop::class, 'favorites', 'user_id', 'shop_id');
    }

    public function shop()
    {
        return $this->hasOne(Shop::class);
    }

}