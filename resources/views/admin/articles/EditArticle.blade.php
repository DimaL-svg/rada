@extends('adminlte::page')

@section('title', 'Редагування статті')

@section('content_header')
    {{-- Виводимо заголовок статті прямо в шапку для зручності --}}
    <h1>Редагувати: {{ $article->title }}</h1>
@stop

@section('content')
<div class="card card-primary">
    {{-- Маршрут UPDATE: відправляємо дані конкретної статті за її ID --}}
    <form action="{{ route('admin.articles.update', $article->id) }}" method="POST">
        @csrf {{-- Токен безпеки --}}
        @method('PUT') {{-- Команда для Laravel: "Це запит на ОНОВЛЕННЯ даних" --}}
        
        <div class="card-body">
            {{-- Поле заголовку: через value підставляємо поточну назву з бази --}}
            <div class="form-group">
                <label for="title">Заголовок статті</label>
                <input type="text" name="title" class="form-control" id="title" value="{{ $article->title }}" required>
            </div>

            {{-- Вибір категорії з автоматичним підсвічуванням поточної --}}
            <div class="form-group">
                <label for="category_id">Категорія</label>
                <select name="category_id" class="form-control">
                    @foreach($categories as $category)
                        {{-- Якщо ID категорії збігається з ID в статті — додаємо атрибут selected --}}
                        <option value="{{ $category->id }}" {{ $article->category_id == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Контент статті: вставляємо існуючий текст між тегами textarea для TinyMCE --}}
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
    toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline | link  table | align lineheight | checklist numlist bullist indent outdent | removeformat',
    //TODO: НАЛАГОДИТИ media
    
    
    // --- 1. ЗАВАНТАЖЕННЯ ФАЙЛІВ (PDF, DOC) ---
    file_picker_callback: (callback, value, meta) => {
        const input = document.createElement('input'); // Створюємо вікно вибору файлу
        input.setAttribute('type', 'file');
        
        if (meta.filetype === 'file') {
            input.setAttribute('accept', '.pdf,.doc,.docx,.xls,.xlsx,.zip,.txt');
        }

        input.onchange = function () {
            const file = this.files[0];
            const formData = new FormData();
            formData.append('_token', '{{ csrf_token() }}'); // Токен для проходження захисту сервера
            formData.append('file', file);

            // Fetch: відправляє файл на сервер (маршрут admin.upload) без перезавантаження
            fetch('{{ route("admin.upload") }}', { method: 'POST', body: formData })
            .then(response => response.json())
            .then(json => {
                // Коли файл зберігся, вставляємо посилання на нього в текст редактора
                if (json && json.location) {
                    callback(json.location, { text: file.name, title: file.name });
                }
            });
        };
        input.click();
    },

    // --- 2. ЗАВАНТАЖЕННЯ КАРТИНОК (Drag&Drop) ---
    images_upload_url: '{{ route("admin.upload") }}',
    automatic_uploads: true,
    images_upload_handler: function (blobInfo) {
        return new Promise((resolve, reject) => {
            const xhr = new XMLHttpRequest();
            xhr.open('POST', '{{ route("admin.upload") }}');
            
            xhr.onload = function() {
                const json = JSON.parse(xhr.responseText);
                // Повертаємо URL картинки для її відображення в редакторі
                if (xhr.status !== 200 || !json.location) { reject('Помилка'); return; }
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