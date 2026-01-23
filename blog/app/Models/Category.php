<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    // Поля, які можна записувати
    protected $fillable = ['name', 'slug', 'parent_id', 'pos'];

    /**
     * ЗВ'ЯЗОК З ПІДКАТЕГОРІЯМИ
     * Дозволяє створювати випадаюче меню (батько -> діти).
     */
    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id')->orderBy('pos');
    }

    /**
     * ЗВ'ЯЗОК З БАТЬКІВСЬКОЮ КАТЕГОРІЄЮ
     * Дозволяє підпункту меню дізнатися, до якого головного розділу він належить.
     */
    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    /**
     * ЗВ'ЯЗОК ЗІ СТАТТЯМИ
     * Одна категорія може містити багато статей.
     */
    public function articles()
    {
        return $this->hasMany(Article::class);
    }
}