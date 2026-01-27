<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Http\Controllers\Admin\CategoryController;
class Category extends Model
{
    protected $fillable = [
        'name', 
        'slug', 
        'parent_id', 
        'pos'
    ];
    // Отримуємо батьківську категорію
    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    // Отримуємо всі дочірні підкатегорії (для випадаючого меню)
    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id')->orderBy('pos', 'asc');
    }

    // Зв'язок зі статтями
    public function articles()
    {
        return $this->hasMany(Article::class);
    }
}