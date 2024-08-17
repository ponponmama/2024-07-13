@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/choose.css') }}">
@endsection

@section('content')
<div class="choose-container">
    <div class="choose-section">
    <p class="text-2">ご予約には登録またはログインが必要です</p>   
    <p class="text">ログインまたは登録お願いします</p>
        <div class="link-button">
            <a href="{{ route('login') }}" class="a-link">ログイン</a>
            <a href="{{ route('register') }}" class="a-link">新規登録</a>
        </div>
    </div>
</div>
@endsection