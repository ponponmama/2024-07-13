<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ShopController;
use Illuminate\Http\Request; 
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ShopManagerController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\QRController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// 店舗一覧ページ表示用,検索機能
Route::get('/', [ShopController::class, 'index'])->name('shops.index');

// 登録
Route::get('register', [AuthController::class, 'showRegistrationForm'])->name('register.form');
Route::post('register', [AuthController::class, 'register'])->name('register');
// ログイン
Route::get('login', [AuthController::class, 'showLoginForm'])->name('login.form');
Route::post('login', [AuthController::class, 'login'])->name('login');
// ログアウト
Route::post('logout', [AuthController::class, 'logout'])->name('logout');
// メール認証ルート
Route::get('/email/verify/{id}/{hash}', [AuthController::class, 'verify'])
    ->middleware(['auth', 'signed'])
    ->name('verification.verify');
Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->middleware('auth')->name('verification.notice');
// メール認証リンクの再送信
Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return back()->with('message', '認証メールを再送信しました。');
})->middleware(['auth', 'throttle:6,1'])->name('verification.resend');

// その他の一般user用認証済みルート
Route::middleware('auth')->group(function () {
    // Thanksページ
    Route::get('thanks', [AuthController::class, 'showThanksPage'])->name('thanks');
    //予約ありがとうございますのページ
    Route::get('/reservation/done', [ReservationController::class, 'done'])->name('reservation.done');
    //MYページ表示
    Route::get('/mypage', [UserController::class, 'mypage'])->name('mypage');
    //支払いページ表示
    Route::get('/payment', [PaymentController::class, 'showForm'])->name('payment.form');
    //支払い
    Route::post('/payment', [PaymentController::class, 'processPayment'])->name('payment.process');
    //MYページに予約情報を取得表示
    Route::get('/reservations/my', [ReservationController::class, 'myReservations'])->name('reservations.my');
    //ＭＹページから予約の変更
    Route::put('/reservations/{id}', [ReservationController::class, 'update'])->name('reservations.update');
    //ＭＹページで予約の削除
    Route::delete('/reservations/{id}', [ReservationController::class, 'destroy'])->name('reservations.destroy');

    //レビュー
    Route::post('/reviews', [ReviewController::class, 'store'])->name('reviews.store');
    //お気に入り追加と解除
    Route::post('/shops/{shop}/favorite', [FavoriteController::class, 'favorite'])->name('shops.favorite');
    Route::delete('/shops/{shop}/unfavorite', [FavoriteController::class, 'unfavorite'])->name('shops.unfavorite');

    // done.blade.phpから戻った時の店舗詳細表示用
    Route::get('/shops/{id}', [ShopController::class, 'shopDetails'])->name('shop.details');

    //詳しく見るボタンクリック 店舗の詳細と予約ページを表示
    Route::get('/shops/{id}/reservation', [ShopController::class, 'shopDetails'])->name('reservation.show');

   // 予約関連のルート index,store,show,edit,update,destroy
    Route::resource('reservations', ReservationController::class);
    //予約ページへ戻る
    Route::get('/reservations/create', [ReservationController::class, 'create'])->name('reservations.create');
    
});

// Admin用のルート
Route::middleware(['auth', 'role:1'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::get('/admin/manage-shop-managers', [AdminController::class, 'manageShopManagers'])->name('admin.manage.shop_managers');
    Route::post('/admin/shop-managers', [AdminController::class, 'createShopManager'])->name('admin.create.shop_manager');
    Route::delete('/admin/users/{user}', [AdminController::class, 'destroy'])->name('admin.users.destroy');
});

// Shop Manager用のルート
Route::middleware(['auth', 'role:2'])->group(function () {
    Route::get('/shop-manager/dashboard', [ShopManagerController::class, 'index'])->name('shop_manager.dashboard');
    Route::get('/shop-manager/manage-shop', [ShopManagerController::class, 'manageShop'])->name('manage.shop');
    Route::get('/shop-manager/reservations', [ShopManagerController::class, 'showReservations'])->name('shop_manager.reservations');
    // 店舗情報の更新
    Route::put('/shop-manager/shop/{id}', [ShopManagerController::class, 'update'])->name('shop_manager.update');
    // 店舗情報の編集ページ
    Route::get('/shop-manager/shop/{id}/edit', [ShopManagerController::class, 'edit'])->name('shop_manager.edit');
    //予約照合
    Route::get('/shop-manager/verify-reservation/{reservationId}', [ShopManagerController::class, 'verifyReservation'])->name('shop_manager.verify_reservation');

   // QRコードの予約情報取得関連
    Route::post('/reservation/details', [ReservationController::class, 'getReservationDetails'])->name('reservation.details');
});

//require __DIR__.'/auth.php';
