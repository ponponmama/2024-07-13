@extends('admin.app_admin')

@section('css')
    <link rel="stylesheet" href="{{ asset('admin_shop_css/manage-shop.css') }}">
@endsection

@section('content')
<div class="manage_content">
    @if (session('success'))
        <div class="alert-success">
            {{ session('success') }}
        </div>
    @endif
    @if (session('error'))
        <div class="alert-danger">
            {{ session('error') }}
        </div>
    @endif
    <h1 class="Edit_Shop_Information">店舗情報</h1>
    <form action="{{ route('shop_manager.update', $shop->id) }}" method="POST" enctype="multipart/form-data" class="manage_form">
        @csrf
        @method('PUT')
        <div class="shop_name_content">
            <label class="label_shop_name">店舗名</label>
            <span class="shop_name_text">{{ $shop->shop_name }}</span>
        </div>
        <div class="input-group-description">
            <label for="description" class="label_description">店舗紹介</label>
            <textarea id="description" name="description" class="description_text">{{ $shop->description }}</textarea>
        </div>
        <span class="business_hours">営業時間</span>
        <div class="input-group-time">
            <img src="{{ asset('images/clock.svg') }}" alt="" class="icon-img">
            <label for="open_time" class="label_open_time">オープン</label>
            <input type="time" id="open_time" name="open_time" value="{{ \Carbon\Carbon::parse($shop->open_time)->format('H:i') }}" class="input_open_time">
        </div>
        <div class="input-group-time">
            <img src="{{ asset('images/clock.svg') }}" alt="" class="icon-img">
            <label for="close_time" class="label_close_time">クローズ</label>
            <input type="time" id="close_time" name="close_time" value="{{ \Carbon\Carbon::parse($shop->close_time)->format('H:i') }}" class="input_close_time">
        </div>
        <div class="input-group">
            <img src="{{ asset('images/img.png') }}" alt="" class="icon-img">
            <label for="image" class="label_image">写真</label>
            <input type="file" id="image" name="image" class="input_image" >
            <label for="image" class="custom-file-upload">
                <i class="fa-cloud-upload">
                    <span id="file-name"></span>
                </i>写真を選択
            </label>
        </div>
        <div class="up_date_button_container">
            <button type="submit" class="up_date_button">更新する</button>
        </div>
    </form>
    <h2 class="confirm_text">更新された情報はこちらで確認できます</h2>
    <div class="image-section">
        <img src="{{ asset('storage/' . $shop->image) }}" alt="{{ $shop->shop_name }}" class="shop_image">
        <p class="shop-guide">
            @foreach ($shop->areas as $area)
                ＃{{ $area->area_name }}
            @endforeach
            @foreach ($shop->genres as $genre)
                ＃{{ $genre->genre_name }}
            @endforeach
        </p>
        <p class="description">{{ $shop->description }}</p>
    </div>
    <h3 class="business_hours_up">営業時間:{{ \Carbon\Carbon::parse($shop->open_time)->format('H:i') }}～{{ \Carbon\Carbon::parse($shop->close_time)->format('H:i') }}</h3>
</div>
@endsection