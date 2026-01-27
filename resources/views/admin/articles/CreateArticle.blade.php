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
    
    // Вибір документів з ПК
    file_picker_types: 'file image media',
    file_picker_callback: (callback, value, meta) => {
        const input = document.createElement('input');
        input.setAttribute('type', 'file');
        if (meta.filetype === 'file') { input.setAttribute('accept', '.pdf,.doc,.docx,.xls,.xlsx'); }

        input.onchange = function () {
            const file = this.files[0];
            const formData = new FormData();
            formData.append('_token', '{{ csrf_token() }}');
            formData.append('file', file);

            fetch('{{ route("admin.upload") }}', { method: 'POST', body: formData })
                .then(response => response.json())
                .then(json => {
                    if (json.location) { callback(json.location, { text: file.name }); }
                });
        };
        input.click();
    },

    // Завантаження фото (Drag&Drop)
    images_upload_url: '{{ route("admin.upload") }}',
    automatic_uploads: true,
    images_upload_handler: function (blobInfo) {
        return new Promise((resolve, reject) => {
            const xhr = new XMLHttpRequest();
            xhr.open('POST', '{{ route("admin.upload") }}');
            xhr.onload = function() {
                const json = JSON.parse(xhr.responseText);
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