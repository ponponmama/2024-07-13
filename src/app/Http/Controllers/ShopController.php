<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Shop;
use App\Models\Area;
use App\Models\Genre;
use App\Models\Reservation;
use Carbon\Carbon;
use App\Services\ShopService;
use Illuminate\Support\Facades\Log;


class ShopController extends Controller
{
    protected $shopService;

    public function __construct(ShopService $shopService)
    {
        $this->shopService = $shopService;
    }

    // done.blade.phpから戻った時の店舗詳細表示用
    public function shopDetails(Request $request,$id)
    {
        Log::info('Received date: ' . $request->input('date'));

        $shop = Shop::findOrFail($id);
        $current = Carbon::now();
        $inputDate = $request->input('date');
        $date = $inputDate ? new Carbon($inputDate) : $current;

        $openTime = $shop->open_time;
        $closeTime = $shop->close_time;
        $start = new Carbon($date->format('Y-m-d') . ' ' . $openTime);
        $end = new Carbon($date->format('Y-m-d') . ' ' . $closeTime);

        // 現在の時間が営業終了時間と同じかそれを過ぎているかチェック
        if ($current->greaterThanOrEqualTo($end)) {
            // 営業時間を過ぎている場合、日付を次の日に設定
            $date = $current->copy()->addDay();
        }

        Log::info('Received date: ' . $date->toDateString()); // 日付をログに記録

        // 営業時間の取得
        $times = $this->shopService->getBusinessHours($openTime, $closeTime, $date->format('Y-m-d'), $current);

        $reservation = Reservation::where('shop_id', $id)->latest()->first(); 

        return view('shops.detail', [
            'shop' => $shop,
            'date' => $date->format('Y-m-d'),
            'times' => $times,
            'reservation' => $reservation,
        ]);
    }

    // 店舗一覧を表示,検索フォームに渡す。
    public function index(Request $request)
    {
        $query = Shop::with(['areas', 'genres']); // 関連データを事前にロード

        if ($request->has('search-area') && $request->input('search-area') != '') {
            $query->whereHas('areas', function ($q) use ($request) {
                $q->where('id', $request->input('search-area'));
            });
        }

        if ($request->has('search-genre') && $request->input('search-genre') != '') {
            $query->whereHas('genres', function ($q) use ($request) {
                $q->where('id', $request->input('search-genre'));
            });
        }

        if ($request->has('search-shop__name') && $request->input('search-shop__name') != '') {
            $query->where('shop_name', 'like', '%' . $request->input('search-shop__name') . '%');
        }

        $shops = $query->get();
        $areas = Area::all();
        $genres = Genre::all();

        return view('shops.index', ['shops' => $shops, 'areas' => $areas, 'genres' => $genres]);
    }

    public function shopDetailsOrChoose($id)
    {
        if (auth()->check()) {
            return $this->shopDetails(request(), $id);
        } else {
            return redirect()->route('choose');
        }
    }
}
