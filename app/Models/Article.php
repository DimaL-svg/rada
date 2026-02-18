<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class Article extends Model
{
    use SoftDeletes; // Дозволяє видаляти статтю в "кошик" (deleted_at)

    // Вказуємо конкретну назву таблиці в базі даних
    protected $table = 'articles_laravel'; 

    // Дозволяємо масове заповнення цих полів
    protected $fillable = [
        'category_id', 'title', 'content', 'slug',
        'seo_title', 'seo_desc','user_id', 'is_active'
    ];
    public function author()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    /**
     * ЗВ'ЯЗОК З КАТЕГОРІЄЮ
     * Кожна стаття належить до однієї конкретної категорії.
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    // App\Models\Article.php

    public function user(): BelongsTo
    {
        // Переконайся, що в таблиці articles колонку з ID автора звати 'user_id'
        return $this->belongsTo(User::class, 'user_id');
    }
    public function toSearchableArray(): array
    {
        return [
            'id'      => (int) $this->id,
            'title'   => $this->title,
            'content' => $this->content, // Використовуємо реальну назву колонки
        ];
    }
    }