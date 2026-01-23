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
    View::composer('*', function ($view) {
        $menuCategories = Category::with('children')
            ->whereNull('parent_id')
            ->orderBy('pos')
            ->get();

        $view->with('menuCategories', $menuCategories);
    });
}
}