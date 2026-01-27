<?php
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\PageController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ArticleController;
use App\Http\Controllers\Admin\UploadController;
use App\Http\Controllers\Admin\CategoryController as AdminCategory; 
use App\Http\Controllers\Auth\LoginController;

// Публічні маршрути
// --- ПУБЛІЧНІ МАРШРУТИ (Для людей) ---
Route::get('/', [PageController::class, 'rada'])->name('rada');
Route::get('/category/{slug}', [PageController::class, 'showCategory'])->name('category.show');

// --- АВТОРИЗАЦІЯ ---
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.post');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');


// --- АДМІН-ПАНЕЛЬ ---
Route::middleware(['auth'])->prefix('admin')->group(function () {
    
    // Редірект на сторінку з статтями
   Route::get('/', [DashboardController::class, 'index'])->name('admin');

    // Статті
    Route::resource('articles', ArticleController::class)->names('admin.articles');

    // Категорії (Використовуємо імпортований AdminCategory)
    Route::resource('categories', AdminCategory::class)->names('admin.categories');

    // Завантаження файлів
    Route::post('upload-file', [UploadController::class, 'upload'])->name('admin.upload');
    //
    Route::post('categories/reorder', [AdminCategory::class, 'reorder'])->name('admin.categories.reorder');
    Route::post('admin/categories/store', [CategoryController::class, 'store'])->name('admin.categories.store');
});

Route::get('/setup-site', function () {
    // Створюємо зв'язок папок
    Artisan::call('storage:link');
    // Очищуємо кеш налаштувань
    Artisan::call('config:clear');
    return 'Налаштування оновлено! Перевірте фото в статтях.';
});