<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Article;  
use App\Models\Category;
use Illuminate\Support\Str;
use App\Http\Requests\Admin\ArticleRequest;

class ArticleController extends Controller
{
    /**
     * СПИСОК СТАТЕЙ
     */
    public function index()
    {
        $articles = Article::with('category')->latest()->paginate(5);

        return view('admin.articles.ShowArticle', compact('articles'));
    }

    /**
     * СТОРІНКА СТВОРЕННЯ
     */
    public function create()
    {
        $categories = Category::all(); 
        return view('admin.articles.CreateArticle', compact('categories'));
    }

    /**
     * ЗБЕРЕЖЕННЯ В БАЗУ
     */
public function store(ArticleRequest $request)
{
    // Якщо дані не валідні, код нижче навіть не запуститься
    $data = $request->validated();
    $data['slug'] = Str::slug($request->title);

    Article::create($data);

    return redirect()->route('admin.articles.index');
}

    /**
     * РЕДАГУВАННЯ
     */
    public function edit($id)
    {
        $article = Article::findOrFail($id);
        $categories = Category::all(); 
        
        return view('admin.articles.EditArticle', compact('article', 'categories'));
    }

    /**
     * ОНОВЛЕННЯ ДАНИХ
     */
public function update(ArticleRequest $request, $id)
{
    $article = Article::findOrFail($id);
    $article->update($request->validated());
    return redirect()->route('admin.articles.index');
}

    /**
     * ВИДАЛЕННЯ (soft-delete - на майбутнє)
     */
    public function destroy($id)
    {
        $article = Article::findOrFail($id);
        $article->delete();

        return redirect()->route('admin.articles.index');
    }
}