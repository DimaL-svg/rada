@extends('templates.rada')

{{-- Пункт 2.3: Динамический заголовок для Google --}}
@section('title', 'Головна сторінка — Рада директорів')

@section('content')
    <h1>Головна</h1>

    @foreach($articles as $article)
        <div class="article">
            {{-- Шапка статьи --}}
            <div class="flex-article">
                <div class="news-title">
                    {{-- В новой таблице это поле 'title' (в старой было 'name') --}}
                    {{ $article->title }}
                </div>
                <div class="news-date">
                    {{-- В Laravel по стандарту используем 'created_at', --}}
                    {{-- который мы заполнили историческими датами (2009 и т.д.) --}}
                    {{ $article->created_at->format('d.m.Y') }}
                </div>
            </div>    
            
            <div class="article-body">
                {{-- Выводим контент и чистим старые ссылки --}}
                {!! str_replace('http://rada-directoriv.ks.ua', '', $article->content) !!}
            </div>
        </div>
    @endforeach

    {{-- Пункт 2.1: Навигация по 1669 записям --}}
    <div class="old-school-nav">
        {{ $articles->links() }}
    </div>
@endsection