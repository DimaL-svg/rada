{{-- ПІДКЛЮЧЕННЯ: Беремо головний каркас сайту (шапка, меню, футер) --}}
@extends('templates.MainTemplate')

{{-- ЗАГОЛОВОК ВКЛАДКИ: Якщо ми в категорії — пишемо її назву, якщо ні — стандартний текст --}}
@section('title', $category->name ?? 'Головна сторінка — Рада директорів')

@section('content')
    {{-- ПЕРЕВІРКА КАТЕГОРІЇ: Якщо користувач перейшов у розділ, виводимо його назву --}}
    @if(isset($category))
        <h1 class="category-title">{{ $category->name }}</h1>
        <hr>
    @else
        <h1>Головна</h1>
    @endif

    {{-- ПЕРЕВІРКА НАЯВНОСТІ СТАТЕЙ: Якщо в базі є записи — показуємо, якщо ні — текст про порожній розділ --}}
    @if($articles->count() > 0)
        {{-- ЦИКЛ: Виводимо кожну статтю по черзі --}}
        @foreach($articles as $item)
            <div class="article-full">
                <div class="flex-article">
                    {{-- НАЗВА ТА ДАТА: Беремо дані з полів title та created_at --}}
                    <h2 class="news-title">{{ $item->title }}</h2>
                    <div class="news-date">{{ $item->created_at->format('d.m.Y') }}</div>
                </div>

                <div class="article-body">
                    {{-- КОНТЕНТ: 
                         1. str_replace — чистить старі абсолютні посилання (робить їх внутрішніми).
                         2. {!! !!} — малює картинки та текст з HTML-тегами. --}}
                    {!! str_replace('http://rada-directoriv.ks.ua', '', $item->content) !!}
                </div>
                <br>
            </div>
        @endforeach

        {{-- ПАГІНАЦІЯ: Малює кнопки перемикання сторінок (1, 2, 3...) --}}
        <div class="pagination-wrapper">
            {{ $articles->links() }}
        </div>
    @else
        {{-- ПОВІДОМЛЕННЯ: Спрацьовує, якщо в категорії 0 статей --}}
        <div class="alert alert-info">
            У цьому розділі поки немає публікацій.
        </div>
    @endif
@endsection