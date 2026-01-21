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
     * Display a listing of the resource.
     */
    public function index()
    {
    $articles = Article::with('category')->latest()->paginate(10);
    
    return view('admin.articles.index', compact('articles'));
    }

    /**
     * Show the form for creating a new resource.
     */
public function create()
{
    $categories = \App\Models\Category::all(); // Отримуємо всі категорії для списку
    return view('admin.articles.create', compact('categories'));
}

// Зберігає дані
public function store(Request $request)
{
    $request->validate([
        'title' => 'required|max:255',
        'content' => 'required',
        // ВИПРАВЛЕНО: міняємо categories_laravel на categories
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
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
   public function edit($id)
{
    $article = Article::findOrFail($id);
    $categories = Category::all(); // Отримуємо всі категорії для вибору
    
    return view('admin.articles.edit', compact('article', 'categories'));
}

    /**
     * Update the specified resource in storage.
     */
public function update(Request $request, $id)
{
    // Отримуємо статтю з твоєї бази articles_laravel
    $article = Article::findOrFail($id);

    // Валідація вхідних даних
    $validated = $request->validate([
        'title' => 'required|max:255',
        'content' => 'required', // Твій HTML з редактора
        'category_id' => 'required|integer',
    ]);

    // Оновлення запису
    $article->update($validated);

    // Повертаємося до списку з повідомленням про успіх
    return redirect()->route('admin.articles.index')
        ->with('success', 'Статтю оновлено!');
}

    /**
     * Remove the specified resource from storage.
     */
   public function destroy($id)
    {
        $article = Article::findOrFail($id);
        $article->delete();

        return redirect()->route('admin.articles.index')->with('success', 'Статтю видалено!');
    }
}
