<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Shop;

class Reservation extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'shop_id',
        'reservation_datetime',
        'number',
    ];

    protected $dates = ['reservation_datetime'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }

    // 予約に関連する支払い情報を取得するリレーション
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
}
