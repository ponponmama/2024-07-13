<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    // ユーザーリレーション
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // 予約リレーション
    public function reservation()
    {
        return $this->belongsTo(Reservation::class);
    }
}
