<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Shop;
use App\Models\Reservation;
use Illuminate\Support\Facades\Auth;


class ShopManagerController extends Controller
{
    // ショップ管理ダッシュボード表示
    public function index()
    {
        $shopId = Auth::user()->shop->id;
        $reservations = Reservation::where('shop_id', $shopId)->get();

        //dd($shopId, $reservations);

        return view('shop_manager.dashboard', ['reservations' => $reservations]);
    }

    //店舗情報の取得して表示
    public function edit($id)
    {
        $shop = Shop::findOrFail($id);
        return view('shop_manager.manage-shop', compact('shop'));
    }

    //店舗情報の更新処理
    public function update(Request $request, $id)
    {
        $request->validate([
            'description' => 'required|string',
            'open_time' => 'required|date_format:H:i',
            'close_time' => 'required|date_format:H:i',
            'image' => 'sometimes|file|image|max:5000',
        ]);

        $shop = Shop::findOrFail($id);
        $data = [
            'description' => $request->description,
            'open_time' => $request->open_time. ':00',
            'close_time' => $request->close_time. ':00',
        ];

        \Log::info('Open Time:', ['open_time' => $request->open_time]);
        \Log::info('Close Time:', ['close_time' => $request->close_time]);

        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $path = $request->image->store('public/images');
            $data['image'] = $path;
        }

        $shop->update($data);

        return redirect()->route('shop_manager.edit', $id)->with('success', '店舗情報が更新されました。');
    }


    // 管理店舗
    public function manageShop()
    {
        // auth()->id() で現在認証されているユーザーのIDを取得し、そのIDを持つ店舗を検索
        $shop = Shop::where('user_id', auth()->id())->firstOrFail();
        
        return view('shop_manager.manage-shop', compact('shop'));

    }

    public function showReservations()
    {
        // ログインしているユーザーが管理する店舗のIDを取得
        $shopId = Auth::user()->shop_id;  // この部分は適宜調整が必要です

        // その店舗の予約情報を取得
        $reservations = Reservation::where('shop_id', $shopId)->get();

        return view('shop_manager.reservations', ['reservations' => $reservations]);
    }

    // サーバーサイドで動作し、予約情報を照合、該当する予約詳細を表示
    //ログイン済み店舗管理者の店舗idと照合した予約idを元に予約情報を検索、予約詳細表示。存在しない場合はエラーメッセージを表示。
    public function verifyReservation($reservationId)
    {
        $shopId = Auth::user()->shop->id;
        $reservation = Reservation::where('id', $reservationId)
                                ->where('shop_id', $shopId)
                                ->first();

        if (!$reservation) {
            return back()->with('error', '予約が見つかりません。');
        }

        return view('shop_manager.reservation_details', compact('reservation'));
    }

}
