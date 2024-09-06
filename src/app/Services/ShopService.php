<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class ShopService
{
    public function getBusinessHours($openTime, $closeTime, $date, $current)
    {
        $times = [];
        $selectedDate = new Carbon($date);
        $currentDate = $current->format('Y-m-d');
        $start = new Carbon($date . ' ' . $openTime);
        $end = new Carbon($date . ' ' . $closeTime);

        // 営業終了時間の30分前を設定
        $end->subMinutes(30);

        if ($selectedDate->format('Y-m-d') != $currentDate) {
            // 選択された日付が現在の日付と異なる場合、その日の全営業時間を表示
            while ($start < $end) {
                $times[] = $start->format('H:i');
                $start->addMinutes(15);
            }
        } else {
            // 選択された日付が現在の日付と同じ場合
            if ($current->between($start, $end)) {
                // 現在時刻を15分単位に丸める
                $currentStart = $current->copy()->second(0);
                $minute = $currentStart->minute;
                $roundedMinute = $minute + (15 - $minute % 15);
                if ($roundedMinute >= 60) {
                    $currentStart->addHour()->minute(0);
                } else {
                    $currentStart->minute($roundedMinute);
                }

                // 営業終了時間まで15分ごとに時間を追加
                while ($currentStart < $end) {
                    $times[] = $currentStart->format('H:i');
                    $currentStart->addMinutes(15);
                }
            }

            // 翌日の営業時間を追加
            $nextDay = $current->copy()->addDay()->format('Y-m-d');
            $nextStart = new Carbon($nextDay . ' ' . $openTime);
            $nextEnd = new Carbon($nextDay . ' ' . $closeTime);
            while ($nextStart < $nextEnd) {
                $times[] = $nextStart->format('H:i');
                $nextStart->addMinutes(15);
            }
        }

        return $times;
    }
}