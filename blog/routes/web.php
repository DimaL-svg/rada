<?php

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


// --- АДМІН-ПАНЕЛЬ (Тільки для тебе) ---
Route::middleware(['auth'])->prefix('admin')->group(function () {
    
    // Головна сторінка адмінки (Дашборд)
    Route::get('/', [DashboardController::class, 'adminindex'])->name('admin');

    // Статті (Повний набір: список, створення, редагування, видалення)
    // Оскільки ми в групі з префіксом 'admin', шлях буде просто 'articles'
    Route::resource('articles', ArticleController::class)->names('admin.articles');

    // Категорії/Меню
    Route::resource('categories', CategoryController::class)->names('admin.categories');

    // Завантаження файлів для редактора статей
    Route::post('upload-file', [UploadController::class, 'upload'])->name('admin.upload');
});