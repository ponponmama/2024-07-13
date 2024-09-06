<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Review;

class ReviewController extends Controller
{
    //来店後のレビューの投稿をデータベースに保存
    public function store(Request $request)
    {
        // リクエストデータのバリデーション
        $request->validate([
            'shop_id' => 'required|integer',
            'rating' => 'required|integer|min:1|max:5', 
            'comment' => 'required|string'
        ]);

        // レビューデータの作成と保存
        Review::create([
            'user_id' => auth()->id(),
            'shop_id' => $request->shop_id,
            'rating' => $request->rating,
            'comment' => $request->comment
        ]);

        // 前のページにリダイレクトし、成功メッセージをフラッシュセッションに追加
        return redirect()->back()->with('success', 'レビューが投稿されました。');
    }
}
