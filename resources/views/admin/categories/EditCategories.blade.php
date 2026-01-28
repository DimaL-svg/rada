{{-- Підключення базового шаблону AdminLTE --}}
@extends('adminlte::page')

@section('title', 'Редактирование категории')

@section('content_header')
    {{-- Виведення поточної назви категорії в заголовку --}}
    <h1>Редагувати: {{ $category->name }}</h1>
@stop

@section('content')
<div class="card card-info">
    {{-- ФОРМА: Вказуємо маршрут UPDATE та обов'язково передаємо ID категорії --}}
    <form action="{{ route('admin.categories.update', $category->id) }}" method="POST">
        @csrf {{-- Токен безпеки для захисту від CSRF-атак --}}
        @method('PUT') {{-- Маскуємо POST-запит під метод PUT (вимога Laravel для оновлення даних) --}}
        
        <div class="card-body">
            <div class="form-group">
                <label>Назва</label>
                {{-- Атрибут value: підставляємо існуючу назву категорії з бази даних --}}
                <input type="text" name="name" class="form-control" value="{{ $category->name }}" required>
            </div>
        </div>

        <div class="card-footer">
            {{-- Кнопка відправки форми --}}
            <button type="submit" class="btn btn-info">Зберегти</button>
            {{-- Кнопка скасування: просто повертає назад до загального списку --}}
            <a href="{{ route('admin.categories.index') }}" class="btn btn-default">Скасувати</a>
        </div>
    </form>
</div>
@stop