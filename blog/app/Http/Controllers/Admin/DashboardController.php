<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Category;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
     public function adminIndex()
    {
        $stats = [
            'total_articles' => Article::count(),
            'total_categories' => Category::count(),
        ];
        
        return view('adminlte::page', compact('stats'));
    }
}
