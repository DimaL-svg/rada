<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Http\Requests\Admin\CategoryRequest;
class CategoryController extends Controller
{
    /**
     * СПИСОК КАТЕГОРІЙ
     */
    public function index()
    {
        // Отримуємо тільки кореневі категорії (parent_id == null)
        $categories = Category::whereNull('parent_id')
            ->with(['children' => function($query) {
                // Сортуємо вкладені підкатегорії за їхньою позицією
                $query->orderBy('pos', 'asc');
            }])
            ->orderBy('pos', 'asc') // Сортуємо головні категорії
            ->get();

        return view('admin.categories.CategoriesAdmin', compact('categories'));
    }

    /**
     * ФОРМА СТВОРЕННЯ
     */
    public function create()
    {
        // Отримуємо категорії верхнього рівня
        $parentCategories = Category::whereNull('parent_id')->get();
        return view('admin.categories.CreateCategories', compact('parentCategories'));
    }

    /**
     * ЗБЕРЕЖЕННЯ НОВОЇ КАТЕГОРІЇ
     */
public function store(CategoryRequest $request)
{
    $data = $request->validated();
    $data['slug'] = Str::slug($request->name);
    
    // Розрахунок позиції залишаємо тут або виносимо в Model Observer
    $data['pos'] = Category::where('parent_id', $request->parent_id)->max('pos') + 1;
    $data['is_active'] = 1;

    Category::create($data);

    return redirect()->route('admin.categories.index')->with('success', 'Категорію додано!');
}

    /**
     * ФОРМА РЕДАГУВАННЯ
     */
     public function edit(Category $category) 
    {
        // Захист архітектури: не дозволяємо категорії стати дочірньою самої себе
        $allCategories = Category::where('id', '!=', $category->id)->get();
        
        return view('admin.categories.EditCategories', compact('category', 'allCategories'));
    }

    /**
     * ОНОВЛЕННЯ ДАНИХ
     */
public function update(CategoryRequest $request, Category $category)
{
    $data = $request->validated();
    $data['slug'] = Str::slug($request->name);
    
    $category->update($data);

    return redirect()->route('admin.categories.index')->with('success', 'Категорія оновлена!');
}

    /**
     * ВИДАЛЕННЯ КАТЕГОРІЇ
     */
    public function destroy($id)
    {
        $category = Category::withCount('articles')->findOrFail($id);

        // Бізнес-правило: не можна видалити категорію, якщо в ній є контент
        if ($category->articles_count > 0) {
            return redirect()->back()->with(
                'error',
                'Неможливо видалити категорію, у якій є статті!'
            );
        }

        $category->delete();
        return redirect()->back()->with('success', 'Категорію видалено!');
    }

    /**
     * ЗМІНА ПОРЯДКУ 
     */
    public function reorder(Request $request)
    {
        $order = $request->input('order'); 
        foreach ($order as $item) {
            Category::where('id', $item['id'])->update(['pos' => $item['pos']]);
        }
        return response()->json(['status' => 'success']);
    }
}