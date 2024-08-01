<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Shop;
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
    public function showDetails($id)
    {
        $shop = Shop::findOrFail($id);
        $current = Carbon::now();  // 現在の日時を取得
        $closingTime = Carbon::parse($current->format('Y-m-d') . ' ' . $shop->close_time);

        $date = $current->lt($closingTime) ? $current->format('Y-m-d') : $current->addDay()->format('Y-m-d');

        $times = $this->shopService->getBusinessHours($shop->open_time, $shop->close_time, $date);

        return view('shops.detail', [
            'shop' => $shop,
            'date' => $date,
            'times' => $times,
        ]);
    }

    // 店舗一覧を表示,検索フォームに渡す。
    public function index(Request $request)
    {
        $query = Shop::query();

        $filterApplied = false;

            if ($request->has('search-area') && $request->input('search-area') != '') {
            $query->where('area', $request->input('search-area'));
            $filterApplied = true;
        }

        if ($request->has('search-genre') && $request->input('search-genre') != '') {
            $query->where('genre', $request->input('search-genre'));
            $filterApplied = true;
        }

        if ($request->has('search-shop__name') && $request->input('search-shop__name') != '') {
            $query->where('shop_name', 'like', '%' . $request->input('search-shop__name') . '%');
            $filterApplied = true;
        }

        if (!$filterApplied) {
            $shops = Shop::all();
        } else {
            $shops = $query->get();
        }

        $areas = Shop::distinct()->pluck('area');
        $genres = Shop::distinct()->pluck('genre');

        return view('shops.index', ['shops' => $shops, 'areas' => $areas, 'genres' => $genres]);
    }

    
    

    public function search(Request $request)
    {
        Log::info('Search parameters:', $request->all());
        $query = Shop::query();

        if ($request->filled('search-area')) {
            $query->where('area', $request->input('search-area'));
        }

        if ($request->filled('search-genre')) {
            $query->where('genre', $request->input('search-genre'));
        }

        if ($request->filled('search-shop__name')) {
            $query->where('shop_name', 'like', '%' . $request->input('search-shop__name') . '%');
        }

        $shops = $query->get();
        $areas = Shop::distinct()->pluck('area');
        $genres = Shop::distinct()->pluck('genre');
        
        //dd($shops, $areas, $genres);

        Log::info($shops);

        return view('shops.index', compact('shops','areas', 'genres'));
    }

}
