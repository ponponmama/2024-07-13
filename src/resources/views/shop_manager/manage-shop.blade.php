@extends('shop_manager.shop_app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/manage-shop.css') }}">
@endsection

@section('content')
<div class="manage_content">
    @if (session('status'))
        <div class="alert-success">
            {{ session('status') }}
        </div>
    @endif
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <h2 class="Edit_Shop_Information">店舗情報</h2>
    <form action="{{ route('shop_manager.update', $shop->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="shop_name">
            <label>店舗名</label>
            <span class="shop_name_text">{{ $shop->shop_name }}</span>
        </div>
        <div class="description_box">
            <label for="description">店舗紹介</label>
            <textarea id="description" name="description" class="description_text">{{ $shop->description }}</textarea>
        </div>
        <div>
            <h3 class="business_hours">営業時間</h3>
        </div>
        <div class="time_box">
            <div class="business_hours_open">
                <label for="open_time">オープン</label>
                <input type="time" id="open_time" name="open_time" value="{{ \Carbon\Carbon::parse($shop->open_time)->format('H:i') }}">
            </div>
            <div class="business_hours_close">
                <label for="close_time">クローズ</label>
                <input type="time" id="close_time" name="close_time" value="{{ \Carbon\Carbon::parse($shop->close_time)->format('H:i') }}">
            </div>
        </div>
        <div class="business_hours_group">
            <label for="image">写真</label>
            <input type="file" id="image" name="image" class="image">
        </div>
        <button type="submit" class="up_date_button">更新する</button>
    </form>
    <span class="confirm_text">更新された情報はこちらで確認できます</span>
    <div class="image-section">
        <img src="{{ asset($shop->image) }}" alt="{{ $shop->shop_name }}">
            <p class="shop-guide">＃{{ $shop->area }}  ＃{{ $shop->genre }}</p>
            <p class="description">{{ $shop->description }}</p>
    </div>
    <p class="business_hours_up">営業時間:{{ \Carbon\Carbon::parse($shop->open_time)->format('H:i') }}～{{ \Carbon\Carbon::parse($shop->close_time)->format('H:i') }}</p>
</div>
@endsection