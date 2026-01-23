<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Category;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * ГОЛОВНА СТОРІНКА АДМІНКИ (Dashboard)
     * Збирає загальну статистику по всьому сайту та відображає її
     * на головному екрані AdminLTE.
     */
    public function index()
    {
        // Рахуємо кількість записів у базі даних
        $stats = [
            'total_articles'   => Article::count(),   // Всього твоїх 1669+ статей
            'total_categories' => Category::count(),  // Скільки пунктів у меню
        ];
        
        // Віддаємо дані у стандартний шаблон AdminLTE
        return view('adminlte::page', compact('stats'));
    }
}