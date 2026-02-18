@extends('adminlte::page')

@section('title', 'Команда проекту')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>Керування авторами</h1>
        <a href="{{ route('admin.users.create') }}" class="btn btn-success">
            <i class="fas fa-plus"></i> Новий адмін
        </a>
    </div>
@stop

@section('content')
<div class="row">
    <div class="col-md-3">
        <div class="info-box bg-info">
            <span class="info-box-icon"><i class="fas fa-users"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Адміністраторів</span>
                <span class="info-box-number">{{ $users->count() }}</span>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="info-box bg-success">
            <span class="info-box-icon"><i class="fas fa-copy"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Всього статей</span>
                <span class="info-box-number">{{ $users->sum('articles_count') }}</span> </div>
        </div>
    </div>
</div>

<div class="feed-container" style="width: 100%;">
    @foreach($users as $user)
    <div class="article-card {{ !$user->is_active ? 'opacity-75' : '' }}">
        <div class="article-body">
            <div class="article-info">
                <div class="d-flex align-items-center">
                    <h5 class="article-title mb-0 mr-3">{{ $user->name }}</h5>
                    @if($user->id === auth()->id())
                        <span class="badge badge-secondary">Ви</span>
                    @endif
                </div>
                <p class="article-excerpt">
                    Логін: <strong>{{ $user->email }}</strong> | 
                    Написано статей: <span class="badge badge-pill badge-dark">{{ $user->articles_count }}</span>
                </p>
            </div>

            <div class="article-actions">
                @if($user->role !== 'super-admin')
                <form action="{{ route('admin.users.toggle', $user->id) }}" method="POST" class="d-inline">
                    @csrf @method('PATCH')
                    <button class="btn btn-sm {{ $user->is_active === 'true' ? 'btn-outline-warning' : 'btn-success' }}" title="Призупинити/Активувати">
                        <i class="fas {{ $user->is_active === 'true' ? 'fa-pause' : 'fa-play' }}"></i>
                    </button>
                </form>
                @endif
                <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-sm btn-primary">
                    <i class="fas fa-edit"></i> Редагувати
                </a>

                @if($user->role !== 'super-admin')
                <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Видалити користувача?')">
                    @csrf @method('DELETE')
                    <button class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                </form>
                @endif
            </div>
        </div>
    </div>
    <br>
    @endforeach
</div>
@stop