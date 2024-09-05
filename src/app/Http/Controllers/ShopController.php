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

    // 店舗の詳細と予約ページを表示
    public function shopDetails(Request $request,$id)
    {
        Log::info('Received date: ' . $request->input('date'));

        $shop = Shop::findOrFail($id);
        $current = Carbon::now();
        $inputDate = $request->input('date');
        $date = $inputDate ? new Carbon($inputDate) : $current;

        // ユーザーが選択した日付が現在の日付より前の場合、翌日の日付を使用
        if ($date->lessThan($current)) {
            $date = $current->copy()->addDay();
        }

        $openTime = $shop->open_time;
        $closeTime = $shop->close_time;
        $start = new Carbon($date->format('Y-m-d') . ' ' . $openTime);
        $end = new Carbon($date->format('Y-m-d') . ' ' . $closeTime);

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
