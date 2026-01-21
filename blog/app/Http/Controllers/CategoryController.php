<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Article; // Убедись, что модель для твоих 1669 записей называется так
use Illuminate\Http\Request;

class CategoryController extends Controller
{
public function index() 
{
    // Отримуємо головні категорії (де parent_id IS NULL)
    $menuCategories = Category::whereNull('parent_id')
        ->with('children') // Завантажуємо вкладені пункти
        ->orderBy('pos')
        ->get();

    // Отримуємо новини для контенту (Пункт 2.1)
    $articles = Article::where('is_active', 1)
        ->latest('created_at')
        ->paginate(10);

    return view('rada', compact('menuCategories', 'articles'));
}

    // Метод для отображения конкретной категории по клику
public function show($slug)
{
    // 1. Якщо клікнули на "головна", редиректимо
    if ($slug === 'головна') {
        return redirect()->route('rada');
    }

    // 2. Шукаємо категорію
    $category = Category::where('slug', $slug)->firstOrFail();

    // 3. Отримуємо ВСІ статті цієї категорії з пагінацією
    $articles = Article::where('category_id', $category->id)
                       ->where('is_active', 1)
                       ->latest('created_at')
                       ->paginate(15);

    // 4. Якщо стаття лише одна — можна відразу показати її (опціонально)
    // Але краще повертати в’юшку зі списком
    return view('static.article_show', compact('category', 'articles'));
}

}