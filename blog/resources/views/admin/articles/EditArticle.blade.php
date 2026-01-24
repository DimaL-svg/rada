@extends('adminlte::page')

@section('title', 'Редагування статті')

@section('content_header')
    <h1>Редагувати: {{ $article->title }}</h1>
@stop

@section('content')
<div class="card card-primary">
    <form action="{{ route('admin.articles.update', $article->id) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="card-body">
            <div class="form-group">
                <label for="title">Заголовок статті</label>
                <input type="text" name="title" class="form-control" id="title" value="{{ $article->title }}" required>
            </div>

            <div class="form-group">
                <label for="category_id">Категорія</label>
                <select name="category_id" class="form-control">
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ $article->category_id == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="editor">Контент (WYSIWYG)</label>
                <textarea name="content" id="editor">{{ $article->content }}</textarea>
            </div>
        </div>

        <div class="card-footer">
            <button type="submit" class="btn btn-success">Зберегти зміни</button>
            <a href="{{ route('admin.articles.index') }}" class="btn btn-default">Назад до списку</a>
        </div>
    </form>
</div>
@stop
@section('js')
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/lang/summernote-uk-UA.min.js"></script>
@section('css')
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.css" rel="stylesheet">
    <style>
        /* Щоб редактор виглядав акуратніше в AdminLTE */
        .note-editor { margin-bottom: 0; border-radius: 0; }
        .note-status-output { display: none; }
    </style>
@stop
<script>
$(document).ready(function () {
    $('#editor').summernote({
        placeholder: 'Введіть текст статті...',
        height: 400,
        lang: 'uk-UA',
        toolbar: [
            ['style', ['style']],
            ['font', ['bold', 'underline', 'clear']],
            ['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['table', ['table']],
            ['insert', ['link', 'picture', 'video']],
            ['view', ['fullscreen', 'codeview']]
        ],
        callbacks: {
            onImageUpload: function (files) {
                uploadFile(files[0]);
            }
        }
    });

    function uploadFile(file) {
        let data = new FormData();
        data.append('upload', file);
        data.append('_token', '{{ csrf_token() }}');

        $.ajax({
            url: '{{ route('admin.upload') }}',
            type: 'POST',
            data: data,
            contentType: false,
            processData: false,
            success: function (url) {
                if (file.type.startsWith('image')) {
                    $('#editor').summernote('insertImage', url);
                } else {
                    let link = $('<a>')
                        .attr('href', url)
                        .attr('target', '_blank')
                        .text(file.name);

                    $('#editor').summernote('insertNode', link[0]);
                }
            },
            error: function () {
                alert('Помилка завантаження файлу');
            }
        });
    }
    
});

</script>
@stop
