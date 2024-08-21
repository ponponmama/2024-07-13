<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reservation;
use App\Models\Shop;
use App\Http\Requests\StoreReservationRequest;
use Carbon\Carbon;
use App\Services\ShopService;
use Illuminate\Support\Facades\Mail;
use App\Mail\ReservationNotification;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Log;
use App\Mail\ReservationUpdated;


class ReservationController extends Controller
{
    protected $shopService;

    public function __construct(ShopService $shopService)
    {
        $this->shopService = $shopService;
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    //予約一覧表示
    public function index()
    {
        $reservations = Reservation::all();

        return view('reservations.index', compact('reservations'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    //予約作成ページ表示
    public function create(Request $request)
    {
        $reservationDetails = session()->get('reservation_details', null);
        $shop = isset($reservationDetails) ? Shop::find($reservationDetails->shop_id) : null;

        if ($shop) {
            $current = Carbon::now();
            $date = $current->format('Y-m-d');
            $times = $this->shopService->getBusinessHours($shop->open_time, $shop->close_time, $date, $current);
        } else {
            $times = [];
        }

        return view('reservation', [
            'shop' => $shop,
            'reservationDetails' => $reservationDetails,
            'times' => $times
        ]);
    }

    public function updateTimes(Request $request)
    {
        $date = $request->input('date', Carbon::now()->format('Y-m-d'));
        $shopId = $request->input('shop_id');
        $shop = Shop::find($shopId);

        if ($shop) {
            $times = $this->shopService->getBusinessHours($shop->open_time, $shop->close_time, $date, Carbon::now());
        } else {
            $times = [];
        }

        return redirect()->route('reservations.create')->with([
            'times' => $times,
            'date' => $date,
            'shop_id' => $shopId
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreReservationRequest $request)
    {
        Log::info('Store method called');
        $shop = Shop::find($request->shop_id);
        $reservationDateTime = Carbon::createFromFormat('Y-m-d H:i', $request->date . ' ' . $request->time);

        // 予約データの保存
        $reservation = new Reservation();
        $reservation->shop_id = $request->shop_id;
        $reservation->reservation_datetime = $reservationDateTime->format('Y-m-d H:i:s');
        $reservation->number = $request->number;
        $reservation->user_id = auth()->id();

        $reservation->save();

        // QRコードを生成し、ファイルに保存
        $qrCodePath = 'storage/qr_codes/' . $reservation->id . '.png'; // 保存パスを指定
        QrCode::format('png')->size(150)->generate('Reservation ID: ' . $reservation->id, storage_path('app/public/qr_codes/' . $reservation->id . '.png'));

        $reservation->qr_code = $qrCodePath;
        $reservation->save();

        // メール送信
        $user = auth()->user(); // ログインしているユーザー情報を取得
        if ($user) {
            Mail::to($user->email)->send(new ReservationNotification($user, $reservation));
        } else {
            Log::error('User not found for email sending.');
        }

        session()->put('reservation_details', $reservation);
        session()->put('last_visited_shop_id', $reservation->shop_id);

        // 予約完了ページにリダイレクト
        return redirect()->route('reservation.done');
    }

    // 予約完了ページ
    public function done()
    {
        return view('done'); 
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($shopId)
    {
        $shop = Shop::find($shopId);

        return view('reservation', ['shop' => $shop]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit()
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

     //マイページで予約の日時変更と削除
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'date' => 'required|date_format:Y-m-d',
            'time' => 'required|date_format:H:i',
            'number' => 'required|integer|min:1'
        ]);

        $shop = Shop::find($request->shop_id);
        $reservationDateTime = Carbon::createFromFormat('Y-m-d H:i', $request->date . ' ' . $request->time);

        $reservation = Reservation::findOrFail($id);
        $reservation->reservation_datetime = $reservationDateTime->format('Y-m-d H:i:s');
        $reservation->number = $request->number;
        $reservation->save();

        // QRコードを再生成（必要に応じて）
        $qrCodePath = 'storage/qr_codes/' . $reservation->id . '.png'; // 保存パスを指定
        QrCode::format('png')->size(80)->generate('Reservation ID: ' . $reservation->id, storage_path('app/public/qr_codes/' . $reservation->id . '.png'));

        $reservation->qr_code = $qrCodePath;
        $reservation->save();

        $user = auth()->user(); // ログインしているユーザー情報を取得
        if ($user) {
            Mail::to($user->email)->send(new ReservationUpdated ($user, $reservation));
        } else {
            Log::error('User not found for email sending.');
        }

        return redirect()->route('mypage')->with('success', '予約が更新されました。');
    }

    /**
    * ユーザーの予約一覧を表示するメソッド。
    *
    * @return \Illuminate\Http\Response
    */
    public function myReservations()
    {
        $user_id = auth()->id(); // ログインユーザーのIDを取得
        $reservations = Reservation::where('user_id', $user_id)->with('shop')->get(); // ユーザーの予約と関連する店舗情報を取得

        return view('reservations.my', ['reservations' => $reservations]); // ビューにデータを渡す
    } 

    public function destroy($id)
    {
        $reservation = Reservation::findOrFail($id);
        $reservation->delete();
        return redirect()->route('mypage')->with('success', '予約が削除されました。');
    }
    //予約idをQRコードで取得し予約情報を検索し、JSON形式で返す。予約ID、予約日時、人数、顧客名、顧客のメールアドレスを含む
    public function getReservationById($id)
    {
        $reservation = Reservation::with('user')->findOrFail($id);
        return response()->json([
            'id' => $reservation->id,
            'reservation_datetime' => $reservation->reservation_datetime->format('Y-m-d H:i'),
            'number' => $reservation->number,
            'user_name' => $reservation->user->user_name,
            'email' => $reservation->user->email
        ]);
    }
}
