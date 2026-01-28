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
     */
    public function index()
    {
       return redirect()->route('admin.articles.index');
    }
}