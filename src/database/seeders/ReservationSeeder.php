<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Reservation;
use App\Models\User;
use App\Models\Shop;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class ReservationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 一般ユーザーを取得（user_nameが 'test_user' のユーザー）
        $user = User::where('role', 3)->first();

        if (!$user) {
            throw new \Exception("No general user found");
        }

        // 全てのショップを取得
        $shops = Shop::all();

        // ショップが存在しない場合のエラーハンドリング
        if ($shops->isEmpty()) {
            throw new \Exception("No shops found");
        }

        foreach ($shops as $shop) {
            // 各ショップに対して5件の予約を作成
            for ($i = 0; $i < 3; $i++) {

                $user = User::where('role', 3)->inRandomOrder()->first();
                if (!$user) {
                    throw new \Exception("No general user found");
                }

                $lunchDateTime = now()->addDays(rand(1, 30))->hour(12)->minute(0)->second(0);
                $lunchQrCodePath = 'qr_codes/lunch_' . $user->id . '_' . $shop->id . '_' . $lunchDateTime->format('YmdHis') . '.svg';
                QrCode::format('svg')->size(100)->generate('Reservation ID: ' . $user->id, storage_path('app/public/' . $lunchQrCodePath));

                // ランチ予約を作成
                $lunchReservation = Reservation::create([
                    'user_id' => $user->id,
                    'shop_id' => $shop->id,
                    'reservation_datetime' => $lunchDateTime,
                    'number' => rand(1, 10)
                ]);

                // 予約IDを使用してQRコードを生成
                $lunchQrCodePath = 'qr_codes/lunch_' . $lunchReservation->id . '.svg';
                $qrCodeContent = 'Reservation ID: ' . $lunchReservation->id;
                QrCode::format('svg')->size(100)->generate($qrCodeContent, storage_path('app/public/' . $lunchQrCodePath));
                $lunchReservation->qr_code = $lunchQrCodePath;
                $lunchReservation->save();

                $user = User::where('role', 3)->inRandomOrder()->first();
                if (!$user) {
                    throw new \Exception("No general user found");
                }

                // デイナー予約を作成
                $dinnerDateTime = now()->addDays(rand(1, 30))->hour(18)->minute(0)->second(0);
                $dinnerReservation = Reservation::create([
                    'user_id' => $user->id,
                    'shop_id' => $shop->id,
                    'reservation_datetime' => $dinnerDateTime,
                    'number' => rand(1, 10)
                ]);
                // 予約IDを使用してQRコードを生成
                $dinnerQrCodePath = 'qr_codes/dinner_' . $dinnerReservation->id . '.svg';
                $qrCodeContent = 'Reservation ID: ' . $dinnerReservation->id;
                QrCode::format('svg')->size(100)->generate($qrCodeContent, storage_path('app/public/' . $dinnerQrCodePath));
                $dinnerReservation->qr_code = $dinnerQrCodePath;
                $dinnerReservation->save();
            }
        }
    }
}
