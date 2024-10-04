<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>予約完了</title>
    <link rel="stylesheet" href="{{ asset('css/common-auth-styles.css') }}">
</head>
<body class="done_body">
    <div class="thanks-form">
        <h2 class="form-thanks-title">ご予約ありがとうございます</h2>
        <div class="thanks-button-container">
            <a href="{{ route('shop.details', ['id' => session('last_visited_shop_id')]) }}" class="back-link" >戻る</a>
        </div>
    </div>
</body>
</html>