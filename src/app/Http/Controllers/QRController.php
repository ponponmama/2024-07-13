<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reservation;
use Libern\QRCodeReader\QRCodeReader; 


class QRController extends Controller
{
    //指定された予約IDからQRコードをデコードします。
    public function decodeQRFromId($id)
    {   
        // 予約データを取得し、存在しない場合は404エラーを返します。
        $reservation = Reservation::findOrFail($id);
        // QRコードのファイルパスを取得
        $qrCodePath = public_path($reservation->qr_code);
        \Log::info("QR Code Path: " . $qrCodePath);

        try {
            // QRコードリーダーを初期化
            $qrcode = new QRCodeReader();
            // QRコードをデコード
            $text = $qrcode->decode($qrCodePath); // メソッドを変更
        } catch (\Exception $e) {
            // QRコードのデコード中にエラーが発生した場合、エラーメッセージをログに記録し、エラーレスポンスを返します。
            \Log::error("QR Decode error: " . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }

        // デコード結果をセッションに保存し、前のページにリダイレクト
        return redirect()->back()->with('result', $text);
    }
}