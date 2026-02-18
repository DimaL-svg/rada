@extends('adminlte::page')

@section('content')
<div class="card">

    {{-- HEADER --}}
    <div class="card-header">
        <h3 class="card-title">Керування категоріями меню</h3>

        <a href="{{ route('admin.categories.create') }}"
           class="btn btn-primary float-right">
            Додати категорію
        </a>
    </div>

    {{-- BODY --}}
    <div class="card-body p-0">
        <table class="table table-striped">

            {{-- TABLE HEAD --}}
            <thead>
                <tr>
                    <th style="width: 30px"></th>
                    <th>Назва</th>
                    <th>Рівень</th>
                    <th style="width: 150px">Дії</th>
                </tr>
            </thead>

            {{-- SORTABLE BODY --}}
            <tbody id="sortable-categories">

            @foreach ($categories as $category)

                {{-- ===============================
                     ГОЛОВНА КАТЕГОРІЯ
                ================================ --}}
                <tr data-id="{{ $category->id }}"
                    class="main-category table-secondary">

                    {{-- Drag handle (тільки за нього можна тягнути) --}}
                    <td class="drag-handle-main text-primary"
                        style="cursor: move;">
                        <i class="fas fa-arrows-alt-v"></i>
                    </td>

                    {{-- Назва --}}
                    <td>
                        <strong>{{ $category->name }}</strong>
                    </td>

                    {{-- Тип --}}
                    <td>
                        <span class="badge badge-primary">Головна</span>
                    </td>

                    {{-- Дії --}}
                    <td>
                        <a href="{{ route('admin.categories.edit', $category->id) }}"
                           class="btn btn-sm btn-info">
                            <i class="fas fa-edit"></i>
                        </a>

                        <form method="POST"
                              action="{{ route('admin.categories.destroy', $category->id) }}"
                              class="d-inline"
                              onsubmit="return confirm('Ви впевнені?')">
                            @csrf
                            @method('DELETE')

                            <button class="btn btn-sm btn-danger">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>

                {{-- ===============================
                     ПІДКАТЕГОРІЇ
                ================================ --}}
                @foreach ($category->children as $child)

                    <tr data-id="{{ $child->id }}"
                        data-parent="{{ $category->id }}"
                        class="child-row bg-white">

                        {{-- Drag handle підкатегорії --}}
                        <td class="drag-handle-child text-muted"
                            style="cursor: move; padding-left: 20px;">
                            <i class="fas fa-grip-lines"></i>
                        </td>

                        {{-- Назва (без стрілок, лише відступ) --}}
                        <td style="padding-left: 40px;"
                            class="text-muted">
                            {{ $child->name }}
                        </td>

                        {{-- Тип --}}
                        <td>
                            <small class="text-muted">Підкатегорія</small>
                        </td>

                        {{-- Дії --}}
                        <td>
                            <a href="{{ route('admin.categories.edit', $child->id) }}"
                               class="btn btn-sm btn-default">
                                <i class="fas fa-edit"></i>
                            </a>

                            <form method="POST"
                                  action="{{ route('admin.categories.destroy', $child->id) }}"
                                  class="d-inline"
                                  onsubmit="return confirm('Видалити підкатегорію?')">
                                @csrf
                                @method('DELETE')

                                <button class="btn btn-sm btn-outline-danger">
                                    <i class="fas fa-trash"></i>
                                </button>
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

    // Головний контейнер таблиці
    const tableBody = document.getElementById('sortable-categories');

    // Ініціалізація drag & drop
    initMainCategories(tableBody);
    initChildCategories(tableBody);
});

/* =====================================================
   1️⃣ Drag & Drop ГОЛОВНИХ категорій
===================================================== */
function initMainCategories(container) {

    Sortable.create(container, {
        animation: 150,

        // Перетягуються ТІЛЬКИ головні категорії
        draggable: '.main-category',

        // Тягнути можна ТІЛЬКИ за іконку
        handle: '.drag-handle-main',

        onEnd(evt) {
            // Коли рухаємо головну категорію —
            // її підкатегорії повинні рухатись разом з нею
            moveChildrenWithParent(evt.item);

            // Зберігаємо новий порядок у БД
            saveOrder('.main-category');
        }
    });
}

/* =====================================================
   2️⃣ Drag & Drop ПІДКАТЕГОРІЙ
===================================================== */
function initChildCategories(container) {

    Sortable.create(container, {
        animation: 150,

        // Перетягуються тільки підкатегорії
        draggable: '.child-row',

        // Drag handle тільки іконка
        handle: '.drag-handle-child',

        // Забороняємо тягнути в іншу категорію
        onMove(evt) {
            return evt.dragged.dataset.parent === evt.related.dataset.parent;
        },

        onEnd(evt) {
            // Зберігаємо порядок лише для конкретного батька
            const parentId = evt.item.dataset.parent;
            saveOrder(`.child-row[data-parent="${parentId}"]`);
        }
    });
}

/* =====================================================
   3️⃣ Переміщення підкатегорій разом з батьком
===================================================== */
function moveChildrenWithParent(parentRow) {

    const parentId = parentRow.dataset.id;

    const children = document.querySelectorAll(
        `.child-row[data-parent="${parentId}"]`
    );

    let lastRow = parentRow;

    children.forEach(child => {
        lastRow.after(child);
        lastRow = child;
    });
}

/* =====================================================
   4️⃣ Збереження порядку в БД
===================================================== */
function saveOrder(selector) {

    const order = [];

    document.querySelectorAll(selector).forEach((row, index) => {
        order.push({
            id: row.dataset.id,
            pos: index + 1
        });
    });

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