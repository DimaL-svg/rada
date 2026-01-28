@extends('adminlte::page')

@section('content')
<div class="card">
    {{-- ШАПКА: Заголовок та кнопка створення --}}
    <div class="card-header">
        <h3 class="card-title">Керування категоріями меню</h3>
        <a href="{{ route('admin.categories.create') }}" class="btn btn-primary float-right">
             Додати категорію
        </a>
    </div>

    <div class="card-body p-0">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th style="width: 30px"></th> {{-- Стовпчик для іконки перетягування --}}
                    <th>Назва</th>
                    <th>Рівень</th>
                    <th style="width: 150px">Дії</th>
                </tr>
            </thead>

            {{-- ТІЛО ТАБЛИЦІ: id потрібен для ініціалізації SortableJS --}}
            <tbody id="sortable-categories">

            @foreach ($categories as $category)
                {{-- ГОЛОВНА КАТЕГОРІЯ: має спеціальний клас main-category для відокремлення логіки сортування --}}
                <tr data-id="{{ $category->id }}" class="main-category table-secondary">
                    
                    {{-- Ручка перетягування (Drag handle) --}}
                    <td class="drag-handle-main text-primary" style="cursor: move;">
                        <i class="fas fa-arrows-alt-v"></i>
                    </td>

                    <td><strong>{{ $category->name }}</strong></td>

                    <td><span class="badge badge-primary">Головна</span></td>

                    <td>
                        <a href="{{ route('admin.categories.edit', $category->id) }}" class="btn btn-sm btn-info">
                            <i class="fas fa-edit"></i>
                        </a>
                        {{-- Видалення через форму (стандарт Laravel) --}}
                        <form method="POST" action="{{ route('admin.categories.destroy', $category->id) }}" class="d-inline" onsubmit="return confirm('Ви впевнені?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                        </form>
                    </td>
                </tr>

                {{-- ПІДКАТЕГОРІЇ: вкладений цикл для виводу дітей поточної категорії --}}
                @foreach ($category->children as $child)
                    <tr data-id="{{ $child->id }}" 
                        data-parent="{{ $category->id }}" {{-- Прив'язка до батька для JS-перевірок --}}
                        class="child-row bg-white">

                        {{-- Ручка для підкатегорії --}}
                        <td class="drag-handle-child text-muted" style="cursor: move; padding-left: 20px;">
                            <i class="fas fa-grip-lines"></i>
                        </td>

                        {{-- Відступ зліва для візуалізації ієрархії --}}
                        <td style="padding-left: 40px;" class="text-muted">{{ $child->name }}</td>

                        <td><small class="text-muted">Підкатегорія</small></td>

                        <td>
                            <a href="{{ route('admin.categories.edit', $child->id) }}" class="btn btn-sm btn-default">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form method="POST" action="{{ route('admin.categories.destroy', $child->id) }}" class="d-inline" onsubmit="return confirm('Видалити підкатегорію?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            @endforeach

            </tbody>
        </table>
    </div>
</div>
@stop

@section('js')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.14.0/Sortable.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const tableBody = document.getElementById('sortable-categories');

    // Запускаємо окремі налаштування для головних та дочірніх категорій
    initMainCategories(tableBody);
    initChildCategories(tableBody);
});

/* 1️⃣ Сортування ГОЛОВНИХ категорій */
function initMainCategories(container) {
    Sortable.create(container, {
        animation: 150,
        draggable: '.main-category', // Дозволяємо тягнути лише батьківські рядки
        handle: '.drag-handle-main', // Тягнути можна лише за іконку стрілочок
        
        onEnd(evt) {
            // Після того як перетягнули батька, автоматично підтягуємо його дітей під нього
            moveChildrenWithParent(evt.item);
            // Відправляємо новий порядок у БД
            saveOrder('.main-category');
        }
    });
}

/* 2️⃣ Сортування ПІДКАТЕГОРІЙ всередині батька */
function initChildCategories(container) {
    Sortable.create(container, {
        animation: 150,
        draggable: '.child-row',      // Дозволяємо тягнути лише рядки підкатегорій
        handle: '.drag-handle-child', // Своя ручка для перетягування

        // Важливо: забороняємо перетягувати підкатегорію в групу іншого батька
        onMove(evt) {
            return evt.dragged.dataset.parent === evt.related.dataset.parent;
        },

        onEnd(evt) {
            const parentId = evt.item.dataset.parent;
            // Зберігаємо порядок лише для дітей цього конкретного батька
            saveOrder(`.child-row[data-parent="${parentId}"]`);
        }
    });
}

/* 3️⃣ Функція-магніт: тримає дітей поруч із батьком при зміні позиції */
function moveChildrenWithParent(parentRow) {
    const parentId = parentRow.dataset.id;
    const children = document.querySelectorAll(`.child-row[data-parent="${parentId}"]`);
    
    let lastRow = parentRow;
    children.forEach(child => {
        lastRow.after(child); // Переміщуємо кожну дитину відразу після батька/попередньої дитини
        lastRow = child;
    });
}

/* 4️⃣ AJAX-запит: зберігає нову черговість (position) у базі даних */
function saveOrder(selector) {
    const order = [];

    // Збираємо всі ID та їх нові позиції на сторінці
    document.querySelectorAll(selector).forEach((row, index) => {
        order.push({
            id: row.dataset.id,
            pos: index + 1
        });
    });

    // Відправка на сервер через Fetch API
    fetch("{{ route('admin.categories.reorder') }}", {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ order })
    });
}
</script>
@stop