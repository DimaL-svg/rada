<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Article;  
use App\Models\Category;
use Illuminate\Support\Str;

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
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|max:255',
            'content' => 'required',
            'category_id' => 'required|exists:categories,id', 
        ]);

        Article::create([
            'title' => $request->title,
            'content' => $request->content,
            'category_id' => $request->category_id,
            'slug' => Str::slug($request->title), 
        ]);

        return redirect()->route('admin.articles.index')->with('success', 'Статтю успішно створено!');
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
    public function update(Request $request, $id)
    {
        $article = Article::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|max:255',
            'content' => 'required', 
            'category_id' => 'required|integer',
        ]);

        $article->update($validated);

        return redirect()->route('admin.articles.index')
            ->with('success', 'Статтю оновлено!');
    }

    /**
     * ВИДАЛЕННЯ (soft-delete - на майбутнє)
     */
    public function destroy($id)
    {
        $article = Article::findOrFail($id);
        $article->delete();

        return redirect()->route('admin.articles.index')->with('success', 'Статтю видалено!');
    }
}