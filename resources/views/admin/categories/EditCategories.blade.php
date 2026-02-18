@extends('adminlte::page')

@section('title', 'Редактирование категории')

@section('content_header')
    <h1>Редагувати: {{ $category->name }}</h1>
@stop

@section('content')
<div class="card card-info">
    <form action="{{ route('admin.categories.update', $category->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="card-body">
            <div class="form-group">
                <label>Назва</label>
                <input type="text" name="name" class="form-control" value="{{ $category->name }}" required>
            </div>
        
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-info">Зберегти</button>
            <a href="{{ route('admin.categories.index') }}" class="btn btn-default">Скасувати</a>
        </div>
    </form>
</div>
@stop