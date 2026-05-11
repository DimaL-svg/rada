<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Http\Controllers\Admin\CategoryController;
use Illuminate\Database\Eloquent\SoftDeletes;
class Category extends Model
{
    use softdeletes;
    protected $fillable = [
        'name', 
        'slug', 
        'parent_id', 
        'pos'
    ];
    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id')->orderBy('pos', 'asc');
    }
    protected static function boot()
    {
    parent::boot();
    static::deleting(function($category) {
        $category->children()->each(function($child) {
            $child->delete();
        });
    });
    }
    // Зв'язок зі статтями
    public function articles()
    {
        return $this->hasMany(Article::class);
    }
}