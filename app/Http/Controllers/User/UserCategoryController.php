<?php

namespace App\Http\Controllers\User;
use App\Models\Article;
use App\Models\Category;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserCategoryController extends Controller
{
    public function showCategory(Category $category) // Laravel сам знайде категорію за слагом
{
    if ($category->slug === 'головна') {
        return redirect()->route('index');
    }

    // Використовуємо зв'язок (пункт 6)
    $articles = $category->articles() 
        ->where('is_active', 1)
        ->latest()
        ->paginate(5);

    return view('Site.ShowArticle', compact('category', 'articles'));
}
}
