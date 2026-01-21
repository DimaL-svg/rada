<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View; // ПРАВИЛЬНИЙ ШЛЯХ ДО VIEW
use App\Models\Category; // ТВОЯ МОДЕЛЬ КАТЕГОРІЙ

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Цей код передає категорії в твій layout автоматично
        View::composer('layouts.rada', function ($view) {
            $view->with('menuCategories', Category::whereNull('parent_id')
                ->with('children')
                ->orderBy('pos')
                ->get()
            );
        });
    }
}