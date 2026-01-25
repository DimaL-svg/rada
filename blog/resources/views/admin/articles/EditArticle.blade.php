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
    <label for="editor">Контент статті</label>
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
<script src="https://cdn.tiny.cloud/1/w6h1je6qfx845moqe6wig19wpoo78krg2rughpnlfaatb4zd/tinymce/8/tinymce.min.js" referrerpolicy="origin" crossorigin="anonymous"></script>

<script>
  tinymce.init({
    selector: '#editor', 
    language: 'uk',     
    height: 600,
    promotion: false,
    branding: false,
    plugins: [
      'anchor', 'autolink', 'charmap', 'codesample', 'emoticons', 'link', 'lists', 'media', 'searchreplace', 'table', 'visualblocks', 'wordcount',
      'checklist', 'mediaembed', 'advtable', 'advcode', 'typography', 'inlinecss'
    ],
    toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline | link media table | align lineheight | checklist numlist bullist indent outdent | removeformat',
    
    // 1. НАЛАШТУВАННЯ ВИБОРУ ФАЙЛІВ З ПК
    file_picker_types: 'file image media',
    file_picker_callback: (callback, value, meta) => {
        const input = document.createElement('input');
        input.setAttribute('type', 'file');
        
        // Встановлюємо фільтр файлів залежно від типу
        if (meta.filetype === 'file') {
            input.setAttribute('accept', '.pdf,.doc,.docx,.xls,.xlsx,.zip,.txt');
        }

        input.onchange = function () {
            const file = this.files[0];
            const formData = new FormData();
            formData.append('_token', '{{ csrf_token() }}');
            formData.append('file', file);

            // Відправка файлу на сервер
            fetch('{{ route("admin.upload") }}', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(json => {
                if (json && json.location) {
                    // Передаємо шлях до файлу в діалогове вікно TinyMCE
                    callback(json.location, { text: file.name, title: file.name });
                } else {
                    alert('Помилка завантаження файлу');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Помилка при завантаженні на сервер');
            });
        };

        input.click();
    },

    // 2. НАЛАШТУВАННЯ ЗАВАНТАЖЕННЯ ЗОБРАЖЕНЬ (Drag&Drop)
    images_upload_url: '{{ route("admin.upload") }}',
    automatic_uploads: true,
    images_upload_handler: function (blobInfo, progress) {
        return new Promise((resolve, reject) => {
            const xhr = new XMLHttpRequest();
            xhr.open('POST', '{{ route("admin.upload") }}');
            
            xhr.onload = function() {
                if (xhr.status !== 200) {
                    reject('Помилка сервера: ' + xhr.status);
                    return;
                }
                const json = JSON.parse(xhr.responseText);
                if (!json || typeof json.location !== 'string') {
                    reject('Сервер повернув неправильний формат JSON (поле "location")');
                    return;
                }
                resolve(json.location);
            };
            
            const formData = new FormData();
            formData.append('file', blobInfo.blob(), blobInfo.filename());
            formData.append('_token', '{{ csrf_token() }}');
            xhr.send(formData);
        });
    }
  });
</script>
@stop