@extends('admin.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
@endsection


@section('content')
<div class="admin_container">
    <p class="user__name">お疲れ様です！　{{ Auth::user()->user_name }}さん</p>
    <div class="title-box">
        <h2 class="form-title">Shop Manager Registration</h2>
    </div>
    <div class="registration-form">
        <div class="registration-text-box">
            <span class="registration-text">店舗代表者登録</span>
        </div>
        <form action="{{ route('admin.create.shop_manager') }}" method="POST" class="create-form">
            @csrf
            <div class="input-group">
                <div class="icon-container">
                    <img src="{{ asset('images/shop.png') }}" alt="">
                </div>
                <div class="form-group">
                    <select id="shop_id" name="shop_id" required>
                        <option value="">店舗を選択してください</option>
                        @foreach ($shops ?? [] as $shop)
                            <option value="{{ $shop->id }}" {{ old('shop_id') == $shop->id ? 'selected' : '' }}>
                            {{ $shop->shop_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form__error">
                    @error('shop_id')
                        {{ $message }}
                    @enderror
                </div>
            </div>
            <div class="input-group">
                <div class="icon-container">
                    <img src="{{ asset('images/human.png') }}" alt="">
                </div>
                <div class="form-group">
                    <input type="text" id="user_name" name="user_name" placeholder="Username" value="{{ old('user_name') }}">
                </div>
                <div class="form__error">
                    @error('user_name')
                        {{ $message }}
                    @enderror
                </div>
            </div>
            <div class="input-group">
                <div class="icon-container">
                    <img src="{{ asset('images/mail.png') }}" alt="">
                </div>
                <div class="form-group">
                    <input type="email" id="email" name="email" placeholder="Email" value="{{ old('email') }}">
                </div>
                <div class="form__error">
                    @error('email')
                        {{ $message }}
                    @enderror
                </div>
            </div>
            <div class="input-group">
                <div class="icon-container">
                    <img src="{{ asset('images/key.png') }}" alt="">
                </div>
                <div class="form-group">
                    <input type="password" id="password" name="password" placeholder="Password" value="{{ old('password') }}">
                </div>
                <div class="form__error">
                    @error('password')
                        {{ $message }}
                    @enderror
                </div>
            </div>
                <div class="button-container">
                    <button class=" register-button" type="submit">店舗代表者登録</button>
                </div>
        </form>
    </div>
    <div class="registration-form-low">
        <div class="registration-text-box">
            <span class="registration-text-low">新規店舗登録</span>
        </div>    
        <form action="{{ route('admin.create.shop') }}" method="POST" class="create-shop-form" enctype="multipart/form-data">
            @csrf
            <div class="input-group">
                <div class="icon-container">
                    <img src="{{ asset('images/shop.png') }}" alt="">
                </div>
                <div class="form-group">
                    <input type="text" name="shop_name" placeholder="Shop Name" value="{{ old('shop_name') }}">
                </div>
                <div class="form__error">
                    @error('shop_id')
                        {{ $message }}
                    @enderror
                </div>
            </div>
            <div class="input-group">
                <div class="icon-container">
                    <img src="{{ asset('images/description.png') }}" alt="">
                </div>
                <div class="form-group">
                    <textarea name="description" placeholder="Description" class="description-text">{{ old('description') }}</textarea>
                </div>
                <div class="form__error">
                    @error('description')
                        {{ $message }}
                    @enderror
                </div>
            </div>
            <div class="input-group">
                <div class="icon-container">
                    <img src="{{ asset('images/genre.png') }}" alt="">
                </div>
                <div class="form-group">
                    <input type="text" name="genre" placeholder="Genre" value="{{ old('genre') }}">
                </div>
                <div class="form__error">
                    @error('genre')
                        {{ $message }}
                    @enderror
                </div>
            </div>
            <div class="input-group">
                <div class="icon-container">
                    <img src="{{ asset('images/area.png') }}" alt="">
                </div>
                <div class="form-group">
                    <input type="text" name="area" placeholder="Area" value="{{ old('area') }}">
                </div>
                <div class="form__error">
                    @error('area')
                        {{ $message }}
                    @enderror
                </div>
            </div>
            <div class="input-group">
                <div class="icon-container">
                    <img src="{{ asset('images/img.png') }}" alt="">
                </div>
                <div class="form-group">
                    <input type="file" id="image" name="image" class="image" style="display: none;">
                    <label for="image" class="custom-file-upload">
                        <i class="fa fa-cloud-upload"><span id="file-name"></span></i>写真を選ぶ
                    </label>
                </div>
                <div class="form__error">
                    @error('image')
                        {{ $message }}
                    @enderror
                </div>
            </div>
            <div class="input-group-time">
                <div class="icon-container">
                    <img src="{{ asset('images/clock.svg') }}" alt="">
                </div>
                <div class="time">
                    <label for="open_time">オープン</label>
                    <input type="time" id="open_time" name="open_time">
                </div>
            </div>
            <div class="form__error">
                @error('open_time')
                    {{ $message }}
                @enderror
            </div>
            <div class="input-group-time">
                <div class="icon-container">
                    <img src="{{ asset('images/clock.svg') }}" alt="">
                </div>
                <div class="time">
                    <label for="close_time">クローズ</label>
                    <input type="time" id="close_time" name="close_time">
                </div>
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