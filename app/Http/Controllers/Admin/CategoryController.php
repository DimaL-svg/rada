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
    // Получаем только главные категории, отсортированные по позиции, 
    // и сразу подгружаем их дочерние элементы
    $categories = Category::whereNull('parent_id')
        ->with(['children' => function($query) {
            $query->orderBy('pos', 'asc');
        }])
        ->orderBy('pos', 'asc')
        ->get();

    return view('admin.categories.CategoriesAdmin', compact('categories'));
}

    /**
     * СТВОРЕННЯ КАТЕГОРІЇ
     * Додає новий пункт у меню сайту та автоматично генерує URL (slug).
     */
public function create()
{
    // Отримуємо тільки головні категорії для вибору батьківської
    $parentCategories = Category::whereNull('parent_id')->get();
    return view('admin.categories.CreateCategories', compact('parentCategories'));
}
    public function edit($id)
{
    $category = Category::findOrFail($id);
    // Получаем все категории, кроме текущей (чтобы нельзя было сделать категорию дочерней самой себе)
    $allCategories = Category::where('id', '!=', $id)->get();
    
    return view('admin.categories.EditCategories', compact('category', 'allCategories'));
}

public function update(Request $request, $id)
{
    $category = Category::findOrFail($id);

    $request->validate([
        'name' => 'required|max:255|unique:categories,name,' . $category->id,
    ]);

    $category->update([
        'name'      => $request->name,
        'slug'      => \Illuminate\Support\Str::slug($request->name),
        'parent_id' => $request->parent_id,
        'pos'       => $request->pos ?? 0,
    ]);

    return redirect()->route('admin.categories.index')->with('success', 'Категорія обновлена!');
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
public function store(Request $request)
{
    // Валідація
    $validated = $request->validate([
        'name' => 'required|unique:categories,name|max:255',
        'parent_id' => 'nullable|exists:categories,id',
    ]);

    // Автоматично знаходимо останню позицію в цій гілці
    $lastPos = \App\Models\Category::where('parent_id', $request->parent_id)
        ->max('pos') ?? 0;

    // Створення запису
    \App\Models\Category::create([
        'name' => $request->name,
        'slug' => \Illuminate\Support\Str::slug($request->name), // Авто-генерація URL
        'parent_id' => $request->parent_id,
        'pos' => $lastPos + 1, // Ставимо в кінець списку
        'is_active' => 1,
    ]);

    return redirect()->route('admin.categories.index')->with('success', 'Категорію додано!');
}
    public function reorder(Request $request)
{
    $order = $request->input('order');

    foreach ($order as $item) {
        // Оновлюємо позицію кожної категорії за її ID
        \App\Models\Category::where('id', $item['id'])->update(['pos' => $item['pos']]);
    }

    return response()->json(['status' => 'success']);
}
}