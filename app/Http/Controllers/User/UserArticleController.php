<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Article;

class UserArticleController extends Controller
{
public function showArticle(Article $article) // Теж через Binding
{
    return view('Site.SingleArticle', compact('article'));
}
   
}
