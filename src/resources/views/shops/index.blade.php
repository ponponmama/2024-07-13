@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/index.css') }}">
@endsection

@section('search')
<div class="search-content">
    <form class="search-form" action="{{ route('shops.index') }}" method="get">
        <div class="search-form__item">
            <div class="select-wrapper">
                <select class="search-form__item-select" name="search-area">
                    <option value="">{{ __('All area') }}</option>
                    @foreach ($areas as $area)
                        <option value="{{ $area->id }}">{{ $area->area_name }}</option>
                    @endforeach
                </select>
                <span class="custom-select-icon"></span>
            </div>
            <div class="select-wrapper">
                <select class="search-form__item-select" name="search-genre">
                    <option value="">{{ __('All genre') }}</option>
                    @foreach ($genres as $genre)
                        <option value="{{ $genre->id }}">{{ $genre->genre_name }}</option>
                    @endforeach
                </select>
                <span class="custom-select-icon"></span> 
            </div>
            <button type="submit" class="search-button">
                <img src="{{ asset('images/search.png') }}" alt="Search">
            </button>
            <input class="search-form__item-input" type="text" name="search-shop__name" placeholder="{{ __('Search...') }}">
        </div>
    </form>
</div>
@endsection
@section('content')
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    <div class="shop_table">
        @foreach ($shops as $shop)
            <div class="shop_card">
                <img src="{{ asset($shop->image) }}" alt="{{ $shop->shop_name }}">
                <div class="shop_info">
                    <h3 class="shop-name">{{ $shop->shop_name }}</h3>
                    <p class="shop-guide">
                        @foreach ($shop->areas as $area)
                            ＃{{ $area->area_name }}
                        @endforeach
                        @foreach ($shop->genres as $genre)
                            ＃{{ $genre->genre_name }}
                        @endforeach
                    </p>
                    <div class="button-container">
                        <a href="{{ route('shop.details.guest', ['id' => $shop->id]) }}" class="shop-detail">詳しくみる</a>
                        @auth
                            @if(auth()->user()->favorites->contains($shop))
                                <form action="{{ route('shops.unfavorite', $shop) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="favorite-button favorited">❤</button>
                                </form>
                            @else
                                <form action="{{ route('shops.favorite', $shop) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="favorite-button">❤</button>
                                </form>
                            @endif
                        @endauth
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endsection
