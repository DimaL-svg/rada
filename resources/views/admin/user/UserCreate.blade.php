@extends('adminlte::page')

@section('title', 'Додати адміна')

@section('content_header')
    <h1>Створення нового адміністратора</h1>
@stop

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h3 class="card-title">Дані нового співробітника</h3>
            </div>
            
            <form action="{{ route('admin.users.store') }}" method="POST">
                @csrf
                
                <div class="card-body">
                    
                        <div class="col-md-6 form-group">
                            <label for="name">Ім'я</label>
                            <input type="text" name="name" 
                                   class="form-control @error('name') is-invalid @enderror" 
                                   id="name" value="{{ old('name') }}">
                        </div>

          
                        <div class="col-md-6 form-group">
                            <label for="email">Email (Логін)</label>
                            <input type="text" name="email" 
                                   class="form-control @error('email') is-invalid @enderror" 
                                   id="email" value="{{ old('email') }}">
                            @error('email')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>          

                        <div class="col-md-6 form-group">
                            <label for="password">Пароль</label>
                            <input type="password" name="password" 
                                   class="form-control" 
                                   id="password">
                            @error('password')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                </div>

                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Зберегти адміна
                    </button>
                    <a href="{{ route('admin.users.index') }}" class="btn btn-default">
                        Скасувати
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@stop