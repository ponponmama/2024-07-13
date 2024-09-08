<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>登録完了</title>
    <link rel="stylesheet" href="{{ asset('css/common-auth-styles.css') }}">
</head>
<body class="thanks_body">
    <div class="thanks-form">
        <h2 class="form-thanks-title">会員登録ありがとうございます</h2>
        <div class="button-thanks">
            <a href="{{ route('login') }}" class="thanks-button">ログインする</a>
        </div>
    </div>
</body>
</html>