<?php

use Illuminate\Support\Facades\Route;
// Імпортуємо контролери з чіткими назвами
use App\Http\Controllers\User\PageController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ArticleController;
use App\Http\Controllers\Admin\UploadController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController; 
use App\Http\Controllers\Auth\LoginController;

/*
|--------------------------------------------------------------------------
| Публічні маршрути
|--------------------------------------------------------------------------
*/
Route::get('/', [PageController::class, 'rada'])->name('rada');
Route::get('/category/{slug}', [PageController::class, 'showCategory'])->name('category.show');

/*
|--------------------------------------------------------------------------
| Авторизація
|--------------------------------------------------------------------------
*/
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.post');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| Адмін-панель (Доступ тільки для авторизованих)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    
    // Головна сторінка адмінки
    Route::get('/', [DashboardController::class, 'index'])->name('index');

    // Статті 
    Route::resource('articles', ArticleController::class);

    // Категорії
    Route::post('categories/reorder', [AdminCategoryController::class, 'reorder'])->name('categories.reorder');
    Route::resource('categories', AdminCategoryController::class);

    // Завантаження медіа 
    Route::post('upload-file', [UploadController::class, 'upload'])->name('upload');
});