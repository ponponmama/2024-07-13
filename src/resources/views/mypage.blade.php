@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/mypage.css') }}">
@endsection

@section('content')
    <p class="user__name">{{ Auth::user()->user_name }}さん</p>
    <div class="sections-container">
        <div class="left-sections">
            @if ($hideReservation == 0)
                <div class="reservation-section">
                    <h2 class="section-title">予約状況</h2>
                    @if (session('success'))
                        <div class="reservation-success">
                            {{ session('success') }}
                        </div>
                    @endif
                    @foreach ($reservations as $reservation)
                        <div class="reservation-summary">
                            <form action="{{ route('reservations.update', $reservation->id) }}" method="POST" id="update-form-{{ $reservation->id }}">
                                @csrf
                                @method('PUT')
                                <a href="{{ route('mypage', ['hide_reservation' => 1]) }}" class="close-button">
                                <img src="{{ asset('images/cross.png') }}" alt="Close">
                                </a>
                                <div class="reservation-summary-item">
                                    <img src="{{ asset('images/clock.svg') }}" alt="Clock Icon" class="clock-icon">
                                    <p class="reservation-summary-date">予約{{ $reservation->shop->id }}</p>
                                </div>
                                <div class="form-group">
                                    <label>Shop</label>
                                    <div class="select-wrapper">
                                        <span class="summary-name">{{ $reservation->shop->shop_name }}</span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="date">Date</label>
                                    <div class="select-wrapper">
                                        <input type="date" name="date" class="date-label" value="{{ \Carbon\Carbon::parse($reservation->reservation_datetime)->format('Y-m-d') }}">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="time">Time</label>
                                    <div class="select-wrapper">
                                        <select id="time" name="time">
                                            @foreach ($reservation->times as $time)
                                                <option value="{{ $time }}"  {{ \Carbon\Carbon::parse($reservation->reservation_datetime)->format('H:i') == $time ? 'selected' : '' }}>{{ $time }}</option>
                                            @endforeach
                                        </select>
                                        <span class="custom-select-icon"></span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Number</label>
                                    <div class="select-wrapper" >
                                        <select id="number" name="number">
                                            @for ($i = 1; $i <= 20; $i++)
                                                <option value="{{ $i }}" {{ $reservation->number == $i ? 'selected' : '' }}>{{ $i }}人</option>
                                            @endfor
                                        </select>
                                        <span class="custom-select-icon"></span>
                                    </div>  
                                </div>  
                            </form>
                            <div class="reservation-button-container">
                                <div class="edit-button-container">
                                    <button type="submit" class="edit-reservation-button" form="update-form-{{ $reservation->id }}">変更</button>
                                </div>
                                <form action="{{ route('reservations.destroy', $reservation->id) }}" method="POST" class="delete-form">
                                    @csrf
                                    @method('DELETE')
                                        <button type="submit" class="delete-reservation-button">削除</button>
                                </form>
                            </div>
                            <div class="qr-code-container">
                                <img src="{{ asset($reservation->qr_code) }}" alt="QR Code for Reservation {{ $reservation->id }}">
                            </div>
                        </div>
                        <div class="reviews-form">
                            <form action="{{ route('reviews.store') }}" method="POST">
                                @csrf
                                <input type="hidden" name="shop_id" value="{{ $last_visited_shop_id }}">
                                <div class="rating-group">
                                    <label for="rating">評価</label>
                                    <div class="select-wrapper">
                                        <select name="rating" id="rating" class="rating">
                                            @for ($i = 1; $i <= 5; $i++)
                                                <option value="{{ $i }}">{{ $i }}</option>
                                            @endfor
                                        </select>
                                        <span class="rating-select-icon"></span>
                                    </div>
                                </div>
                                <div class="rating-group">
                                    <label for="comment">コメント</label>
                                    <textarea name="comment" id="comment" required></textarea>
                                </div>
                                <button type="submit" class="review-button">レビューを投稿</button>
                            </form>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
        <div class="favorite-shops-section">   
            <h2 class="section-title">お気に入り店舗</h2>
            <div class="favorite-shops">
                @foreach ($favorites as $favorite)
                    <div class="shop_card">
                        <img src="{{ asset($favorite->image) }}" alt="{{ $favorite->shop_name }}">
                        <div class="shop_info">
                            <h3 class="shop-name">{{ $favorite->shop_name }}</h3>
                            <p class="shop-guide">
                                @foreach ($favorite->areas as $area)
                                    ＃{{ $area->area_name }}
                                @endforeach
                                @foreach ($favorite->genres as $genre)
                                    ＃{{ $genre->genre_name }}
                                @endforeach
                            </p>
                            <div class="button-container">
                                <a href="{{ route('shop.details', ['id' => $favorite->id]) }}" class="shop-detail">詳しくみる</a>
                                @auth
                                    @if(auth()->user()->favorites->contains($favorite))
                                        <form action="{{ route('shops.unfavorite', $favorite) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="favorite-button favorited">❤</button>
                                        </form>
                                        @else
                                        <form action="{{ route('shops.favorite', $favorite) }}" method="POST">
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
        </div>
@endsection