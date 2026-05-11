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


    public function user(): BelongsTo
    {
        
        return $this->belongsTo(User::class, 'user_id');
    }
    public function getSeoTitleAttribute($value)
{
    return $value ?: $this->title;
}
    public function getSeoDescAttribute($value)
{

    $source = $value ?: $this->content;


    $decoded = html_entity_decode($source, ENT_QUOTES, 'UTF-8');


    $plainText = strip_tags($decoded);


    $cleanText = preg_replace('/\s+/', ' ', $plainText);


    return mb_substr(trim($cleanText), 0, 160);
}


public function getSeoKeywordsAttribute()
{

    $keywords = str_replace([' ', ',', '.', '!', '?'], ', ', $this->title);
    return mb_strtolower($keywords);
}
    }