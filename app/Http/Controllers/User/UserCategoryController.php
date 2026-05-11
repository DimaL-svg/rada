<?php

namespace App\Http\Controllers\User;
use App\Models\Article;
use App\Models\Category;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserCategoryController extends Controller
{
    public function showCategory(Category $category) 
{
    if ($category->slug === 'головна') {
        return redirect()->route('index');
    }

    
    $articles = $category->articles() 
        ->where('is_active', 1)
        ->latest()
        ->paginate(5);

    return view('Site.ShowArticle', compact('category', 'articles'));
}
}
