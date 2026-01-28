<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Category;
use Illuminate\Http\Request;

class PageController extends Controller
{
    /**
     * Головна сторінка 
     */
    public function rada()
    {
        $articles = Article::where('is_active', 1)
            ->latest()
            ->paginate(5); 

        $title = 'Головна сторінка'; 

        return view('Site.ShowArticle', compact('articles', 'title'));
    }

    /**
     * Показ конкретної категорії 
     */
    public function showCategory($slug)
    {
        if ($slug === 'головна') {
            return redirect()->route('rada');
        }
        $category = Category::where('slug', $slug)->firstOrFail();
        $articles = Article::where('category_id', $category->id)
            ->where('is_active', 1)
            ->latest()
            ->paginate(5); 
        return view('Site.ShowArticle', compact('category', 'articles'));
    }
}