@extends('templates.MainTemplate')

{{-- Динамический заголовок: название категории или "Головна сторінка" --}}
@section('title', $category->name ?? 'Головна сторінка — Рада директорів')

@section('content')
    @if(isset($category))
        <h1 class="category-title">{{ $category->name }}</h1>
        <hr>
    @else
        <h1>Головна</h1>
    @endif

    @if($articles->count() > 0)
        @foreach($articles as $item)
            <div class="article-full">
                <div class="flex-article">
                    <h2 class="news-title">{{ $item->title }}</h2>
                    <div class="news-date">{{ $item->created_at->format('d.m.Y') }}</div>
                </div>

                <div class="article-body">
                    {{-- Очищаем старые ссылки --}}
                    {!! str_replace('http://rada-directoriv.ks.ua', '', $item->content) !!}
                </div>
                <br>
            </div>
        @endforeach

        {{-- Пагинация --}}
        <div class="pagination-wrapper">
            {{ $articles->links() }}
        </div>
    @else
        <div class="alert alert-info">
            У цьому розділі поки немає публікацій.
        </div>
    @endif
@endsection
