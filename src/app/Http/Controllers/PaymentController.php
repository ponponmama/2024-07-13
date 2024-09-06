<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Charge;

class PaymentController extends Controller
{
    //支払いページの表示
    public function showForm()
    {
        return view('payment.form');
    }

    //Stripeを使用した支払い処理
    public function processPayment(Request $request)
    {
        // Stripe APIキーの設定
        Stripe::setApiKey(env('STRIPE_SECRET'));

        try {
            // 支払いの実行
            $charge = Charge::create([
                'amount' => 100, // 金額
                'currency' => 'jpy', // 通貨コードは小文字で 'jpy'
                'description' => 'テスト',
                'source' => $request->stripeToken,
            ]);

            // 支払い成功時のリダイレクト
            return back()->with('success_message', '支払い完了!');
        } catch (\Exception $e) {
             // エラー発生時の処理
            return back()->with('error_message', 'Error: ' . $e->getMessage());
        }
    }
}