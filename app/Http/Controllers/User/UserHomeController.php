<?php

namespace App\Http\Controllers\User;
use App\Models\Article;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserHomeController extends Controller
{
   public function rada()
    {
     $articles = Article::where('is_active', 1)->latest()->paginate(5);
    $title = 'Головна сторінка';

    return view('Site.ShowArticle', compact('articles', 'title'));
    }
}
