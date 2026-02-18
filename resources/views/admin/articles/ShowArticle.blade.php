@extends('adminlte::page')

@section('title', 'Список статей')
@section('content_top_nav_left')
    <span  style="font-size: 1.4rem; display: flex; jcustify-content: center; align-items: center;">
        Керування статтями
    </span>
@stop
@section('content_header')

    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-12 d-flex align-items-center justify-content-between">
                
                
                <div class="card-tools">
                    <a href="{{ route('admin.articles.create') }}" class="btn btn-success btn-sm">
                        <i class="fas fa-plus"></i> Додати новий запис
                    </a>
                </div>
            </div>
        </div>
    </div>
@stop
@push('css')
<style>

</style>
@endpush
@section('content')
    <div class="card">    
        </div>
        <div class="card-body p-0">
<table class="table table-bordered table-hover">
    <thead class="thead-dark" style="text-align:center">
        <tr>
            <th>Перегляд публікації</th>
            <th style="width: 150px">Категорія</th>
            <th style="width: 120px">Дії</th>
        </tr>
    </thead>
    <tbody>
        @foreach($articles as $article)
            <tr>
<td>
    {{-- Заголовок і дата --}}
    <div class="p-2 mb-2" style="background-color: #00a65a; color: white; font-weight: bold; display: flex; justify-content: space-between; border-radius: 4px;">
        <span>{{ $article->title }}</span>
        <small>{{ $article->created_at ? $article->created_at->format('d/m/Y') : '14/01/2026' }}</small>
    </div>

    {{-- Короткий прев'ю-текст без картинок --}}
    <div class="article-preview-text" style="color: #666; font-size: 0.9rem;">
        {{ Str::limit(html_entity_decode(strip_tags($article->content)), 300) }}
    </div>
</td>
                <td class="text-center" style="vertical-align: middle; justify-content: center; align-items: center; padding: 0;">
                    <span class="badge badge-info">{{ $article->category->name ?? 'Головна' }}</span>
                </td>
    <td class="text-center" style="vertical-align: middle; justify-content: center; padding: 0;">
    <div class="btn-group">
        <a href="{{ route('admin.articles.edit', $article->id) }}" class="btn btn-primary btn-sm">
            <i class="fas fa-edit"></i>
        </a>
        <form action="{{ route('admin.articles.destroy', $article->id) }}" method="POST" class="d-inline">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Видалити?')">
                <i class="fas fa-trash"></i>
            </button>
        </form>
    </div>
    </td>
            </tr>
        @endforeach
    </tbody>
    
</table>
<div class="card-footer clearfix">
    <div style="display: flex; justify-content: center; align-items: center;">
        {{ $articles->links('pagination::bootstrap-4') }}
    </div>
</div>
@stop
