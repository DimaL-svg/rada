<?php

use App\Http\Controllers\PageController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\ArticleController;
use App\Http\Controllers\Admin\UploadController;
use App\Http\Controllers\ProfileController;

// Публічні маршрути
Route::get('/', [PageController::class, 'rada'])->name('rada');
Route::get('/category/{slug}', [CategoryController::class, 'show'])->name('category.show');

// Авторизація
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.post');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Адмін-панель
Route::middleware(['auth'])->group(function () {
    // Головна та панель
    Route::get('/admin', [PageController::class, 'adminIndex'])->name('admin');
    Route::get('/adminpanel', [PageController::class, 'adminIndex'])->name('adminpanel'); // Можна об'єднати

    // Статті (Resource)
    Route::resource('admin/articles', ArticleController::class)->names('admin.articles');

    // Профіль
 

    // Завантаження файлів
    Route::post('/admin/upload-file', [UploadController::class, 'upload'])->name('admin.upload');
});