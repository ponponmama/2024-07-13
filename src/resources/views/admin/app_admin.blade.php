<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rese</title>
    <link rel="stylesheet" href="{{ asset('css/common.css') }}">
    <link rel="stylesheet" href="{{ asset('css/navbar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}">
    @yield('css')
</head>

<body class="app_body">
    <main>
        <div class="container">
            <div class="content">
                <div class="header">
                    <div class="left-group">
                        @include('partials.navbar')
                        <h1 class="top_logo">Rese</h1>
                    </div>
                </div>
                @yield('content')
            </div>
        </div>
    </main> 
</body>
</html>