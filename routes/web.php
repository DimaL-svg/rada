<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\UserHomeController;
use App\Http\Controllers\User\UserCategoryController;
use App\Http\Controllers\User\UserArticleController;

/*********************
 *  User Routes
 *********************/
Route::get('/', [UserHomeController::class, 'rada'])->name('index');


Route::get('/category/{category:slug}', [UserCategoryController::class, 'showCategory'])->name('category.show');


Route::get('/article/{article:slug}', [UserArticleController::class, 'showArticle'])->name('article.show');


Route::post('/logout', function () {
    auth()->logout();
    return redirect('/');
})->name('logout');

