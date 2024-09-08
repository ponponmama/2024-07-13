<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="{{ asset('css/common-auth-styles.css') }}">
</head>
<body class="login_body">
    <div class="login-form">
        <div class="title-box">
            <h2 class="form-title">Login</h2>
        </div>
        <form action="{{ route('login') }}" method="POST">
            @csrf
            <div class="icon-container">
                <img src="{{ asset('images/mail.png') }}" alt="">
            </div>
            <div class="form-group">
                <input type="email" id="email" name="email" placeholder="Email" class="input_email" required>
            </div>
            <div class="icon-container">
                <img src="{{ asset('images/key.png') }}" alt="">
            </div>
            <div class="form-group">
                <input type="password" id="password" name="password" placeholder="Password" value="{{ old('password') }}" class="input_password" required>
            </div>
            <div class="button-container">
                <button class="login-button" type="submit">ログイン</button>
            </div>
        </form>
    </div>
</body>
</html>