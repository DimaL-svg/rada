<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    // Связь должна указывать на этот же класс Category
    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id')->orderBy('pos');
    }

    // Обратная связь (необязательно, но полезно): ребенок знает своего родителя
    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }
}