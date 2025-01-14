<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReservationController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//QRコードから読み取った予約idの情報(サーバサイド)
Route::get('/reservation/{id}', [ReservationController::class, 'getReservationById']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
