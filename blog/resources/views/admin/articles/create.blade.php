@extends('adminlte::page')

@section('title', 'Додати нову статтю')

@section('content_header')
    <h1>Створити нову публікацію</h1>
@stop

@section('content')
<div class="card card-success">
    <form action="{{ route('admin.articles.store') }}" method="POST">
        @csrf
        <div class="card-body">
            <div class="form-group">
                <label for="title">Заголовок статті</label>
                <input type="text" name="title" class="form-control" placeholder="Введіть заголовок" required>
            </div>

            <div class="form-group">
                <label for="category_id">Категорія</label>
                <select name="category_id" class="form-control">
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="editor">Контент</label>
                <textarea name="content" id="editor"></textarea>
            </div>
        </div>

        <div class="card-footer">
            <button type="submit" class="btn btn-success">Опублікувати</button>
            <a href="{{ route('admin.articles.index') }}" class="btn btn-default">Скасувати</a>
        </div>
    </form>
</div>
@stop

@section('css')
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.css" rel="stylesheet">
@stop

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/lang/summernote-uk-UA.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#editor').summernote({
                height: 400,
                lang: 'uk-UA',
                callbacks: {
                    onImageUpload: function(files) { uploadFile(files[0]); }
                }
            });
            // Використовуй ту саму функцію uploadFile, що й у edit.blade.php
        });
    </script>
@stop