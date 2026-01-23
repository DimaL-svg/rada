<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Article; // Додано для коректної роботи index та destroy
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    /**
     * СПИСОК КАТЕГОРІЙ
     * Відображає всі категорії (пункти меню) для їхнього редагування чи видалення.
     */
    public function index() 
    {
        // Отримуємо категорії з підрахунком статей у кожній
        $categories = Category::withCount('articles')
            ->orderBy('pos')
            ->get();

        return view('admin.categories.index', compact('categories'));
    }

    /**
     * СТВОРЕННЯ КАТЕГОРІЇ
     * Додає новий пункт у меню сайту та автоматично генерує URL (slug).
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:categories|max:255',
        ]);

        Category::create([
            'name'      => $request->name,
            'slug'      => Str::slug($request->name), // Авто-генерація посилання
            'parent_id' => $request->parent_id,       // Для підпунктів меню
            'pos'       => $request->pos ?? 0,        // Позиція (черговість) у меню
        ]);

        return redirect()->back()->with('success', 'Категорію успішно додано!');
    }

    /**
     * ВИДАЛЕННЯ КАТЕГОРІЇ
     * Дозволяє видалити категорію лише якщо в ній немає жодної статті.
     */
    public function destroy($id)
    {
        // Знаходимо категорію разом із кількістю прив'язаних статей
        $category = Category::withCount('articles')->findOrFail($id);

        // Захист від видалення категорій з контентом
        if ($category->articles_count > 0) {
            return redirect()->back()->with(
                'error',
                'Неможливо видалити категорію, у якій є статті!'
            );
        }

        $category->delete();

        return redirect()->back()->with('success', 'Категорію видалено!');
    }
}