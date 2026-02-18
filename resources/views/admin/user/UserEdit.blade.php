@extends('adminlte::page')

@section('title', 'Редагувати адміна')

@section('content_header')
    <h1>Редагування профілю: {{ $user->email }}</h1>
@stop

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card card-info card-outline">
            <div class="card-header">
                <h3 class="card-title">Зміна облікових даних</h3>
            </div>
            
            <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
                @csrf
                @method('PUT')
                
            
                     <div class="col-md-6 form-group">
                            <label for="name">Ім'я</label>
                            <input type="text" name="name" 
                                   class="form-control @error('name') is-invalid @enderror" 
                                   id="name" value="{{ old('name', $user->name) }}">
                        </div>
                        <div class="col-md-6 form-group">
                            <label for="email">Логін</label>
                            <input type="text" name="email" 
                                   class="form-control @error('email') is-invalid @enderror" 
                                   id="email" value="{{ old('email', $user->email) }}">
                            @error('email')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>          

                        <div class="col-md-6 form-group">
                            <label for="password">Новий пароль</label>
                            <input type="password" name="password" 
                                   class="form-control @error('password') is-invalid @enderror" 
                                   id="password" placeholder="Залиште порожнім, щоб не змінювати">
                            @error('password')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
               

                <div class="card-footer">
                    <button type="submit" class="btn btn-info">
                        <i class="fas fa-sync-alt"></i> Оновити дані
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