@extends('layouts.default')

<!-- タイトル -->
@section('title','マイページ')

<!-- css読み込み -->
@section('css')
<link rel="stylesheet" href="{{ asset('/css/index.css')  }}" >
<link rel="stylesheet" href="{{ asset('/css/mypage.css')  }}" >
@endsection

<!-- 本体 -->
@section('content')

@include('components.header')
<div class="container">
    <div class="user">
            <div class="user__info">
                <div class="user__img">
                    @if (isset($user->profile->img_url))
                        <img class="user__icon" src="{{env('AWS_S3_BASE_URL')}}/{{env('AWS_BUCKET')}}/{{$user->profile->img_url}}">
                    @else
                        <img id="myImage" class="user__icon" src="{{ asset('img/icon.png') }}" alt="">
                    @endif
                </div>
                <p class="user__name">{{$user->name}}</p>
            </div>
            <div class="mypage__user--btn">
            <a class="btn2" href="/mypage/profile">プロフィールを編集</a>
            </div>
    </div>
    <div class="border">
        <ul class="border__list">
            <li><a href="/mypage?page=sell">出品した商品</a></li>
            <li><a href="/mypage?page=buy">購入した商品</a></li>
        </ul>
    </div>
    <div class="items">
        @foreach ($items as $item)
        <div class="item">
            <a href="/item/{{$item->id}}">
                @if ($item->sold())
                    <div class="item__img sold">
                        <img src="{{env('AWS_S3_BASE_URL')}}/{{env('AWS_BUCKET')}}/{{$item->img_url}}" alt="商品画像">
                    </div>
                @else
                    <div class="item__img">
                        <img src="{{env('AWS_S3_BASE_URL')}}/{{env('AWS_BUCKET')}}/{{$item->img_url}}" alt="商品画像">
                    </div>
                @endif
                <p class="item__name">{{$item->name}}</p>
            </a>
        </div>
        @endforeach
    </div>
</div>
@endsection
