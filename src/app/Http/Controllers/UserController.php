<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Services\ShopService;


class UserController extends Controller
{
    protected $shopService;

    public function __construct(ShopService $shopService)
    {
        $this->shopService = $shopService;
    }

    public function mypage(Request $request)
    {
        $user = Auth::user(); 
        $reservations = $user->reservations()->with('shop')->get(); 
        $favorites = $user->favorites;
        $hideReservation = $request->query('hide_reservation', 0);

        $last_visited_shop_id = null;
        if ($reservations->isNotEmpty()) {
            $last_visited_shop_id = $reservations->last()->shop_id;
        }

        foreach ($reservations as $reservation) {
            $shop = $reservation->shop;
            $current = Carbon::now();
            $date = $current->format('Y-m-d');
            $reservation->times = $this->shopService->getBusinessHours($shop->open_time, $shop->close_time, $date,$current);
            \Log::info('Times for reservation ' . $reservation->id . ': ' . json_encode($reservation->times));
        }

        return view('mypage', [
            'reservations' => $reservations,
            'favorites' => $favorites,
            'hideReservation' => $hideReservation,
            'last_visited_shop_id' => $last_visited_shop_id
        ]);
    }
}
