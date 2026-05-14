<?php

namespace App\Models;

use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements FilamentUser
{
    use HasFactory, Notifiable;

    public $timestamps = false;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',      
        'is_active', 
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Перевірка доступу до адмін-панелі.
     * Тут ми дозволяємо вхід тільки адмінам та активним редакторам.
     */
    public function canAccessPanel(Panel $panel): bool
    {
        return $this->is_active && in_array($this->role, ['admin', 'editor']);
    }

    /**
     * Автоматичне призначення ролі при реєстрації.
     */
    protected static function booted()
    {
        static::creating(function ($user) {
            $user->role = $user->role ?? 'editor';
            $user->is_active = $user->is_active ?? true;
        });
    }

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    public function articles()
    {
        return $this->hasMany(Article::class, 'user_id');
    }
}