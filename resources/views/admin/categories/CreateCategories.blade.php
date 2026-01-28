{{-- Підключаємо шаблон адмінки AdminLTE --}}
@extends('adminlte::page')

@section('title', 'Додати категорію')

@section('content_header')
    <h1>Створити нову категорію</h1>
@stop

@section('content')
<div class="card card-primary">
    {{-- ФОРМА: Відправляє дані на метод store для створення нового запису --}}
    <form action="{{ route('admin.categories.store') }}" method="POST">
        @csrf {{-- ЗАХИСТ: Токен безпеки Laravel --}}
        
        <div class="card-body">
            {{-- НАЗВА: Текстове поле з валідацією --}}
            <div class="form-group">
                <label>Назва категорії</label>
                {{-- Клас is-invalid підсвітить поле червоним, якщо буде помилка --}}
                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" required>
                
                {{-- ВИВІД ПОМИЛКИ: Якщо назва вже зайнята або занадто коротка --}}
                @error('name')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            {{-- ВІЄРАРХІЯ: Вибір батьківської категорії --}}
            <div class="form-group">
                <label>Батьківська категорія</label>
                <select name="parent_id" class="form-control">
                    {{-- Якщо вибрано порожнє значення — категорія стане "кореневою" (рівень 0) --}}
                    <option value="">Зробити головною</option>
                    
                    {{-- Цикл виводить існуючі категорії, щоб ми могли "вкласти" нову в них --}}
                    @foreach($parentCategories as $parent)
                        <option value="{{ $parent->id }}">{{ $parent->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="card-footer">
            <button type="submit" class="btn btn-primary">Зберегти</button>
            <a href="{{ route('admin.categories.index') }}" class="btn btn-default">Назад</a>
        </div>
    </form>
</div>
@stop