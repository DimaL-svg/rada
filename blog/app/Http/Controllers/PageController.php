<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Article;
class PageController
{
    public function rada()
{

$articles = Article::where('is_active', 1)->latest()->paginate(10);

    return view('static.posts', compact('articles'));
}
public function adminIndex()
{
    $articles = Article::where('is_active', 1)
    ->latest('created_at') // Використовуємо created_at замість date
    ->paginate(10);
}
}
