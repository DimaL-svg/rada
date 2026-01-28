<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Article extends Model
{
    use SoftDeletes; 

    protected $table = 'articles_laravel'; 


    protected $fillable = [
        'category_id', 'title', 'content', 'slug',
        'seo_title', 'seo_desc', 'is_active'
    ];

    /**
     * ЗВ'ЯЗОК З КАТЕГОРІЄЮ
     * Кожна стаття належить до однієї конкретної категорії.
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}