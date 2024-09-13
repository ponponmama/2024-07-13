@extends('admin.app_admin')

@section('css')
    <link rel="stylesheet" href="{{ asset('admin_shop_css/admin.css') }}">
@endsection

@section('content')
<div class="admin_container">
    <h1 class="form-title">Shop Manager Registration</h1>    
    <p class="user__name">お疲れ様です！　{{ Auth::user()->user_name }}さん</p>
    <div class="shop_manager_form">
        <h2 class="manage_admin">店舗代表者登録</h2>
        <form action="{{ route('admin.create.shop_manager') }}" method="POST" class="create-form">
            @csrf
            <div class="input-group">
                <img src="{{ asset('images/shop.png') }}" alt="" class="icon-img">
                <div class="select-wrapper">
                    <select id="shop_id" name="shop_id"  class="select_shop_id">
                        <option value="">店舗を選択してください</option>
                        @foreach ($shops ?? [] as $shop)
                            <option value="{{ $shop->id }}" {{ old('shop_id') == $shop->id ? 'selected' : '' }}>
                                {{ $shop->shop_name }}
                            </option>
                        @endforeach
                    </select>
                    <span class="custom-select-icon"></span>
                </div>
            </div>
            <div class="form__error">
                @error('shop_id')
                    {{ $message }}
                @enderror
            </div>
            <div class="input-group">
                <img src="{{ asset('images/human.png') }}" alt="" class="icon-img">
                <input type="text" id="user_name" name="user_name" placeholder="Username" value="{{ old('user_name') }}" class="input_user_name">
            </div>
            <div class="form__error">
                @error('user_name')
                    {{ $message }}
                @enderror
            </div>
            <div class="input-group">
                <img src="{{ asset('images/mail.png') }}" alt="" class="icon-img">
                <input type="email" id="email" name="email" placeholder="Email" value="{{ old('email') }}" class="input_email">
            </div>
            <div class="form__error">
                @error('email')
                    {{ $message }}
                @enderror
            </div>
            <div class="input-group">
                <img src="{{ asset('images/key.png') }}" alt="" class="icon-img">
                <input type="password" id="password" name="password" placeholder="Password" value="{{ old('password') }}" class="input_password">
            </div>
            <div class="form__error">
                @error('password')
                    {{ $message }}
                @enderror
            </div>
            <div class="button-container">
                <button class=" register-button" type="submit">店舗代表者登録</button>
            </div>
        </form>
    </div>
    <div class="shop_registration_form">
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
        <h2 class="shop_manage">新規店舗登録</h2>
        <form action="{{ route('admin.create.shop') }}" method="POST" class="create-shop-form" enctype="multipart/form-data">
            @csrf
            <div class="input-group">
                <img src="{{ asset('images/shop.png') }}" alt="" class="icon-img">
                <input type="text" name="shop_name" placeholder="Shop Name" value="{{ old('shop_name') }}" class="input_shop_name">
            </div>
            <div class="form__error">
                @error('shop_name')
                    {{ $message }}
                @enderror
            </div>
            <div class="input-group">
                <img src="{{ asset('images/description.png') }}" alt="" class="icon-img">
                <textarea name="description" placeholder="Description" class="description_text">{{ old('description') }}</textarea>
            </div>
            <div class="form__error">
                @error('description')
                    {{ $message }}
                @enderror
            </div>
            <div class="input-group">
                <img src="{{ asset('images/genre.png') }}" alt="" class="icon-img">
                <input type="text" name="genre_name" placeholder="Genre" value="{{ old('genre_name') }}" class="input_genre">
            </div>
            <div class="form__error">
                @error('genre_name')
                    {{ $message }}
                @enderror
            </div>
            <div class="input-group">
                <img src="{{ asset('images/area.png') }}" alt="" class="icon-img">
                <input type="text" name="area_name" placeholder="Area" value="{{ old('area_name') }}" class="input_area">
            </div>
            <div class="form__error">
                @error('area_name')
                    {{ $message }}
                @enderror
            </div>
            <div class="input-group">
                <img src="{{ asset('images/img.png') }}" alt="" class="icon-img">
                <input type="file" id="image" name="image" class="input_image" >
                <label for="image" class="custom-file-upload">
                    <i class="fa-cloud-upload">
                        <span id="file-name" class="file-name-display"></span>
                    </i>写真を選択
                </label>
            </div>
            <div class="form__error">
                @error('image')
                    {{ $message }}
                @enderror
            </div>
            <div class="input-group-time">
                <img src="{{ asset('images/clock.svg') }}" alt="" class="icon-img">
                <label for="open_time" class="label_open_time">オープン</label>
                <input type="time" id="open_time" name="open_time" class="input_open_time">
            </div>
            <div class="form__error">
                @error('open_time')
                    {{ $message }}
                @enderror
            </div>
            <div class="input-group-time">
                <img src="{{ asset('images/clock.svg') }}" alt="" class="icon-img">
                <label for="close_time" class="label_close_time">クローズ</label>
                <input type="time" id="close_time" name="close_time" class="input_close_time">
            </div>
            <div class="form__error">
                @error('close_time')
                    {{ $message }}
                @enderror
            </div>
            <div class="button-container">   
                <button class="new-register-button" type="submit">新店舗登録</button>
            </div>
        </form>
    </div>
</div>
<script>
document.getElementById('image').addEventListener('change', function() {
    var fileName = this.files[0].name;
    var fileLabel = document.getElementById('file-name');
    fileLabel.textContent = fileName;
});
</script>
@endsection