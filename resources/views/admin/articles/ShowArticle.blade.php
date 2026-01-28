@extends('adminlte::page')

@section('title', 'Список статей')

@section('content_header')
    <h1>Керування статтями</h1>
    <div class="card-tools ml-auto">
        <a href="{{ route('admin.articles.create') }}" class="btn btn-success btn-sm">
            <i class="fas fa-plus"></i> Додати новий запис
        </a>
    </div>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Архів публікацій</h3>
        </div>
       
        <div class="card-body p-0">
            <table class="table table-bordered table-hover">
                <thead class="thead-dark">
                    <tr>
                        <th>Повний перегляд публікації</th>
                        <th style="width: 150px">Категорія</th>
                        <th style="width: 120px">Дії</th>
                    </tr>
                </thead>
                <tbody>
                    {{-- Цикл @foreach: Бере масив статей і по одній виводить їх у рядки таблиці --}}
                    @foreach($articles as $article)
                        <tr>
                            <td>
                                {{-- Шапка: Виводить заголовок та дату, яку Carbon форматує у вигляд День/Місяць/Рік --}}
                                <div class="p-2 mb-2" style="background-color: #00a65a; color: white; font-weight: bold; display: flex; justify-content: space-between;">
                                    <span>{{ $article->title }}</span>
                                    <span>{{ $article->created_at->format('d/m/Y')}}</span>
                                </div>

                                {{-- Контент: {!! !!} рендерить HTML-теги (картинки, списки), які зберіг TinyMCE --}}
                                <div class="article-preview-content" style="max-height: 1000px; overflow-y: auto; border: 1px solid #ddd; padding: 15px; background: #fff;">
                                   {!! $article->content !!}
                                </div>
                            </td>
                            <td class="text-center">
                                {{-- Зв'язок: Модель Article через belongsTo заходить у модель Category і бере назву --}}
                                <span class="badge badge-info">{{ $article->category->name ?? 'Головна' }}</span>
                            </td>
                            <td class="text-center">
                                <div class="btn-group">
                                    {{-- Редагування: Посилання передає ID статті у форму редагування --}}
                                    <a href="{{ route('admin.articles.edit', $article->id) }}" class="btn btn-primary btn-sm" title="Редагувати">
                                        <i class="fas fa-edit"></i>
                                    </a>

                                    {{-- Видалення: Форма надсилає прихований DELETE-запит для видалення запису за ID --}}
                                    <form action="{{ route('admin.articles.destroy', $article->id) }}" method="POST" style="display:inline">
                                        @csrf {{-- Токен безпеки Laravel --}}
                                        @method('DELETE') {{-- Підміна методу POST на DELETE для контролера --}}
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
        </div>

        {{-- Пагінація: Laravel автоматично рахує кількість статей і малює кнопки перемикання сторінок --}}
        <div class="card-footer clearfix">
            {{ $articles->links('pagination::bootstrap-4') }}
        </div>
    </div>
@stop

{{-- Сесія: Виводить спливаюче повідомлення, якщо контролер повернув відповідь 'success' --}}
@if(session('success'))
    <div class="alert alert-success alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <h5><i class="icon fas fa-check"></i> Успішно!</h5>
        {{ session('success') }}
    </div>
@endif