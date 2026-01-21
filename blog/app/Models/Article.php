<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; // Для пункту 2.2 (Кошик)

class Article extends Model
{
    use SoftDeletes; // Активуємо підтримку кошика

    protected $table = 'articles_laravel'; // Вказуємо твою нову таблицю

    protected $fillable = [
        'category_id', 'title', 'content', 
        'seo_title', 'seo_desc', 'is_active'
    ];

    // Зв'язок: стаття належить до категорії
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}