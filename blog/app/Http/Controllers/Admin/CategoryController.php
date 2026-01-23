<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;


class CategoryController extends Controller
{
 public function index() 
    {
        $articles = Article::where('is_active', 1)
            ->latest('created_at')
            ->paginate(10);

        // menuCategories більше не треба передавати вручну!
        return view('rada', compact('articles'));
    }

    // Создание новой категории (Меню)
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:categories|max:255',
        ]);

        Category::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name), // Авто-генерация ссылки
            'parent_id' => $request->parent_id,  // Если это подпункт меню
            'pos' => $request->pos ?? 0,         // Порядок в меню
        ]);

        return redirect()->back()->with('success', 'Категория успешно добавлена!');
    }

    // Удаление категории
    public function destroy($id)
{
    $category = Category::withCount('articles')->findOrFail($id);

    if ($category->articles_count > 0) {
        return redirect()->back()->with(
            'error',
            'Нельзя удалить категорию, в которой есть статьи!'
        );
    }

    $category->delete();

    return redirect()->back()->with('success', 'Категория удалена!');
}

}
