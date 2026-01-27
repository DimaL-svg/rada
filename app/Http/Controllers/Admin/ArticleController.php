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
     * Виводить таблицю всіх статей з пагінацією та назвами категорій.
     */
    public function index()
    {
        $articles = Article::with('category')->latest()->paginate(5);
        
        return view('admin.articles.ShowArticle', compact('articles'));
    }

    /**
     * СТОРІНКА СТВОРЕННЯ
     * Показує форму для написання нової статті.
     */
    public function create()
    {
        $categories = Category::all(); 
        return view('admin.articles.CreateArticle', compact('categories'));
    }

    /**
     * ЗБЕРЕЖЕННЯ В БАЗУ
     * Перевіряє дані та створює новий запис у таблиці статей.
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
     * Знаходить статтю за ID та відкриває форму для її зміни.
     */
    public function edit($id)
    {
        $article = Article::findOrFail($id);
        $categories = Category::all(); 
        
        return view('admin.articles.EditArticle', compact('article', 'categories'));
    }

    /**
     * ОНОВЛЕННЯ ДАНИХ
     * Записує змінений текст та заголовок у вже існуючу статтю.
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
     * ВИДАЛЕННЯ
     * Назавжди видаляє статтю з бази даних.
     */
    public function destroy($id)
    {
        $article = Article::findOrFail($id);
        $article->delete();

        return redirect()->route('admin.articles.index')->with('success', 'Статтю видалено!');
    }
}