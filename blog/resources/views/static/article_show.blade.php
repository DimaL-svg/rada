@extends('layouts.rada')

{{-- Якщо статей багато, беремо заголовок першої або назву категорії --}}
@section('title', $category->name ?? 'Перегляд розділу')

@section('content')
    <div class="articles-list">
        <h1 class="category-title">{{ $category->name }}</h1>
        <hr>

        @if($articles->count() > 0)
            @foreach($articles as $item)
                <div class="article-full">
                    <div class="flex-article">
                        <h2 class="news-title">
                            {{-- Заголовок як посилання (якщо плануєш окрему сторінку для кожної статті) --}}
                            {{ $item->title }}
                        </h2>
                        <div class="news-date">
                            {{ $item->created_at->format('d.m.Y') }}
                        </div>
                    </div>
                    
                    <div class="article-body">
                        {{-- Виводимо контент та очищуємо старі посилання --}}
                        {!! str_replace('http://rada-directoriv.ks.ua', '', $item->content) !!}
                    </div>
                    <br>
                    
                </div>
            @endforeach

            {{-- Пагінація, щоб твої 1559 статей не вантажились на одну сторінку --}}
            <div class="pagination-wrapper">
                {{ $articles->links() }}
            </div>
        @else
            <div class="alert alert-info">
                У цьому розділі поки немає публікацій.
            </div>
        @endif
    </div>
@endsection