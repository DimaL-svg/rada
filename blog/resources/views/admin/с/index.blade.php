@extends('adminlte::page')

@section('title', 'Керування категоріями')

@section('content_header')
    <h1>Керування розділами меню</h1>
@stop

@section('content')
<div class="row">
    {{-- Форма додавання --}}
    <div class="col-md-4">
        <div class="card card-outline card-success">
            <div class="card-header"><h3 class="card-title">Додати розділ</h3></div>
            <form action="{{ route('admin.categories.store') }}" method="POST">
                @csrf
                <div class="card-body">
                    <div class="form-group">
                        <label>Назва розділу</label>
                        <input type="text" name="name" class="form-control" placeholder="Наприклад: Накази" required>
                    </div>
                    <div class="form-group">
                        <label>Позиція</label>
                        <input type="number" name="pos" class="form-control" value="0">
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-success btn-block">Додати до меню</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Таблиця категорій --}}
    <div class="col-md-8">
        <div class="card card-outline card-primary">
            <div class="card-header"><h3 class="card-title">Список категорій</h3></div>
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Назва</th>
                            <th class="text-center">Статей</th>
                            <th class="text-center">Дії</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($categories as $cat)
                        <tr>
                            <td><strong>{{ $cat->name }}</strong></td>
                            <td class="text-center">
                                <span class="badge badge-info">{{ $cat->articles_count }}</span>
                            </td>
                            <td class="text-center">
                                <div class="btn-group">
                                    <a href="{{ route('admin.categories.edit', $cat->id) }}" class="btn btn-primary btn-sm">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.categories.destroy', $cat->id) }}" method="POST">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-danger btn-sm" onclick="return confirm('Видалити?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@stop