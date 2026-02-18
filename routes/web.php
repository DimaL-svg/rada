<?php

use Illuminate\Support\Facades\Route;
// Імпортуємо контролери з чіткими назвами
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ArticleController;
use App\Http\Controllers\Admin\UploadController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController; 
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\User\UserHomeController;
use App\Http\Controllers\User\UserCategoryController;
use App\Http\Controllers\User\UserArticleController;
use App\Http\Controllers\Admin\UserController;
/*
|--------------------------------------------------------------------------
| Публічні маршрути
|--------------------------------------------------------------------------
*/
Route::get('/', [UserHomeController::class, 'rada'])->name('rada');
Route::get('/category/{category:slug}', [UserCategoryController::class, 'showCategory'])->name('category.show');
Route::get('/article/{article:slug}', [UserArticleController::class, 'showArticle'])->name('article.show');
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
    //Дії над користувачами
    Route::patch('users/{user}/toggle', [UserController::class, 'toggleStatus'])
         ->name('users.toggle');
    Route::resource('users', UserController::class);
    // Категорії
    Route::post('categories/reorder', [AdminCategoryController::class, 'reorder'])->name('categories.reorder');
    Route::resource('categories', AdminCategoryController::class);

    // Завантаження медіа 
    Route::post('upload-file', [UploadController::class, 'upload'])->name('upload');
});


