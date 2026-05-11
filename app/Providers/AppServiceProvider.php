<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View; // ПРАВИЛЬНИЙ ШЛЯХ ДО VIEW
use App\Models\Category; // ТВОЯ МОДЕЛЬ КАТЕГОРІЙ
use Illuminate\Support\Facades\Gate;
use App\Models\User;

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

    Gate::define('super-admin', function (User $user) {
        return $user->role === 'super-admin';
    });

}
}