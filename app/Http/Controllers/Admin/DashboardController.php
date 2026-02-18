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
       
       return redirect()->route('admin.articles.index');
    }
}