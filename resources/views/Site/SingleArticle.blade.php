@extends('templates.MainTemplate')

@section('title', $article->title)

@section('content')
    <div class="container">
        <h1  style="width: 100%"    class="news-title">{{ $article->title }}</h1>
        <div  style="width: 100%"   class="news-date">{{ $article->created_at->format('d.m.Y') }}</div>
        <hr>
        <div class="article-body">
            {!! str_replace('http://rada-directoriv.ks.ua', '', $article->content) !!}
        </div>
        <div style="margin-top: 20px;">
            <a href="{{ route('index') }}">← Назад на головну</a>
        </div>
    </div>
    

@endsection