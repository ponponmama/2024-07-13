<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <style>
        .container {
            width: 100%;
            text-align: center;
        }
        .message {
            display: inline-block;
            text-align: left;
            max-width: 600px;
            font-size:1rem;
        }

        .hello {
            display: inline-block;
            text-align: left; /* メッセージ内容を左寄せ */
            max-width: 600px;
            font-size: 1.3rem;
            font-weight: bold;
        }

        .qr_code_img {
            display: block;
            margin: 0 auto; /* 画像を中央に配置 */
        }

    </style>
</head>
<body>
    <div class="container">
        <div class="message">
            <p class="hello">こんにちは、{{ $user->user_name }}さん</p>
            <p>以下の予約が変更されました。</p>
            <p>予約日時: {{ $reservation->reservation_datetime->format('Y-m-d H:i') }}</p>
            <p>人数: {{ $reservation->number }}</p>
            <p>店舗: {{ $reservation->shop->shop_name }}</p>
            <p>QRコードをご来店時にご提示ください。</p>
            <img src="{{ asset($reservation->qr_code) }}" alt="QR Code" class="qr_code_img">
        </div>
    </div>
</body>
</html>