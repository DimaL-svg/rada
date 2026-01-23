<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Category;

class PageController extends Controller
{
    // Головна сторінка для людей
    public function rada()
    {
        $articles = Article::where('is_active', 1)->latest()->paginate(10);
        return view('static.posts', compact('articles'));
    }

    // Головна сторінка адмінки (Дашборд)
    public function adminIndex()
    {
        $stats = [
            'total_articles' => Article::count(),
            'total_categories' => Category::count(),
        ];
        
        return view('adminlte::page', compact('stats'));
    }
}