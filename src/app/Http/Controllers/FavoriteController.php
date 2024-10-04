<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Shop;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{
    //お気に入りの追加
    public function favorite(Shop $shop)
    {
        $user = Auth::user();
        \Log::info('Adding to favorites', ['user_id' => $user->id, 'shop_id' => $shop->id]);

        // 既にお気に入りに追加されているか確認
        $alreadyExists = $user->favorites()->where('shop_id', $shop->id)->exists();

        // お気に入りに追加
        $user->favorites()->syncWithoutDetaching([$shop->id]);

        // 既に存在する場合はupdated_atのみ更新、そうでなければcreated_atとupdated_atを更新
        if ($alreadyExists) {
            $user->favorites()->updateExistingPivot($shop->id, ['updated_at' => now()]);
        } else {
            $user->favorites()->updateExistingPivot($shop->id, ['created_at' => now(), 'updated_at' => now()]);
        }

        \Log::info('Authenticated user:', ['user' => $user]);
        return back()->with('success', 'お気に入りに追加しました！');
    }

    //お気に入りの解除
    public function unfavorite(Shop $shop)
    {
        $user = Auth::user();
        $user->favorites()->detach($shop->id);
        \Log::info('Removing from favorites', ['user_id' => $user->id, 'shop_id' => $shop->id]);
        return back()->with('success', 'お気に入りを解除しました！');
    }
}
