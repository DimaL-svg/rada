<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Category;
use Illuminate\Http\Request;

class PageController extends Controller
{
    /**
     * Головна сторінка — виводить останні 5 активних статей.
     */
    public function rada()
    {
        $articles = Article::where('is_active', 1)
            ->latest()
            ->paginate(5); // Виставляємо 5 записів для головної сторінки

        $title = 'Головна сторінка'; 

        return view('Site.ShowArticle', compact('articles', 'title'));
    }

    /**
     * Показ конкретної категорії — виводить 5 статей цієї категорії.
     */
    public function showCategory($slug)
    {
        // 1. Якщо клікнули на "головна" в меню (зі старих лінків), редиректимо на головний роут
        if ($slug === 'головна') {
            return redirect()->route('rada');
        }

        // 2. Шукаємо категорію за слагом
        $category = Category::where('slug', $slug)->firstOrFail();
        
        // 3. Отримуємо статті цієї категорії з пагінацією по 5
        $articles = Article::where('category_id', $category->id)
            ->where('is_active', 1)
            ->latest()
            ->paginate(5); // Виставляємо 5 записів для категорій

        return view('Site.ShowArticle', compact('category', 'articles'));
    }
}