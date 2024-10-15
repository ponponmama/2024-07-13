<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>予約のお知らせ</title>
    <style>
        .reminder_mail {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f4f4f4;
        }
        .container {
            text-align: center;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }

        .reminder,
        .hello,
        .reminder_text,
        .qr_code_text {
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
<body class="reminder_mail">
    <div class="container">
        <h1 class="reminder">予約リマインダー</h1>
        <p class="hello">こんにちは！ {{ $reservation->user->user_name }}様,</p>
        <p class="reminder_text">こちらは {{ $reservation->reservation_datetime }}にご予約いただいているリマインダーです。</p>
        <p class="qr_code_text">QRコードをご来店時にご提示ください。</>
            <img src="{{ asset($reservation->qr_code) }}" alt="QR Code" class="qr_code_img">
    </div>
</body>
</html>