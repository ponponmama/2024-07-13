<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Genre extends Model
{
    use HasFactory;

    public function shops()
    {
        return $this->belongsToMany(Shop::class, 'shops_genres', 'genre_id', 'shop_id');
    }
}
