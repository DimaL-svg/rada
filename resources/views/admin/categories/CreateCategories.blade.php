@extends('adminlte::page')

@section('title', 'Додати категорію')

@section('content_header')
    <h1>Створити нову категорію</h1>
@stop

@section('content')
<div class="card card-primary">
<form action="{{ route('admin.categories.store') }}" method="POST">
    @csrf
    <div class="form-group">
        <label>Назва категорії</label>
        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" required>
        @error('name')
    <span class="invalid-feedback" role="alert">
        <strong>{{ $message }}</strong>
    </span>
@enderror

    </div>

    <div class="form-group">
        
        <label>Батьківська категорія</label>
        <select name="parent_id" class="form-control">
            
            <option value="">Зробити головною</option>
            @foreach($parentCategories as $parent)
                <option value="{{ $parent->id }}">{{ $parent->name }}</option>
            @endforeach
        </select>
    </div>
    <button type="submit" class="btn btn-primary">Зберегти</button>
</form>
</div>
@stop