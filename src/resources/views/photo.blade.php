@extends('layouts.default')

<!-- タイトル -->
@section('title','localstack確認')

<!-- 本体 -->
@section('content')

@foreach ($list as $photo)
        <hr />
        <div class="media">
            <a class="media-left" href="#">
                {{-- 画像 --}}
                <img width="100px" class="media-object img-thumbnail" src="{{env('AWS_S3_BASE_URL')}}/{{env('AWS_BUCKET')}}/{{$photo['name']}}">
            </a>
            <div class="media-body">
                {{-- 画像情報 --}}
                <p>date : {{ $photo['date'] }}</p>
                <p>name : {{ $photo['name'] }}</p>
                <p>size : {{ ($photo['size'] > (1024*1024)) ? round($photo['size']/1024/1024,2).'MB' : round($photo['size']/1024).'KB'}} </p>
            </div>
        </div>
        @endforeach
<form action="/photo" method="post" enctype="multipart/form-data">
    @csrf
    <input type="file" name="photo">
    <button>送信</button>
</form>
@endsection