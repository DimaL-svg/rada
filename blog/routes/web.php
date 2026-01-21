<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PageController;
use App\Http\Controllers\AdminPanel;
use App\Http\Controllers\CategoryController;

// Головна сторінка
Route::get('/', [PageController::class, 'rada'])->name('rada');

// Маршрут для категорій та статей (Ті самі 1669 записів)
Route::get('/category/{slug}', [App\Http\Controllers\CategoryController::class, 'show'])->name('category.show');
Route::post('/logout', function () {
    Auth::logout();
    return redirect('/');
})->name('logout');


// 1. Показуємо сторінку логіна (саме цей маршрут шукає Laravel)
Route::get('/login', function () {
    return view('auth.login'); 
})->name('login');

// 2. Обробляємо натискання кнопки
Route::post('/login', function (Illuminate\Http\Request $request) {
    $user = \App\Models\User::first();
    if ($user) {
        auth()->login($user);
        return redirect()->route('admin.articles.index');
    }
    return "Користувачів у базі не знайдено!";
})->name('login.post');

Route::middleware(['auth'])->group(function () {
    Route::get('/admin', [PageController::class, 'adminIndex'])->name('admin');
    Route::get('/adminpanel', [AdminPanel::class, 'adminpanel'])->name('adminpanel');


    Route::resource('admin/articles', App\Http\Controllers\Admin\ArticleController::class)
          ->names('admin.articles'); 

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
Route::post('/admin/upload-file', function (Illuminate\Http\Request $request) {
    if ($request->hasFile('upload')) {
        $file = $request->file('upload');
        $extension = strtolower($file->getClientOriginalExtension());
        $fileName = time() . '_' . $file->getClientOriginalName();
        
        $subFolder = in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp']) ? 'images' : 'files';
        $destinationPath = public_path('ckfinder/userfiles/' . $subFolder);
        
        $file->move($destinationPath, $fileName);
        
        return asset('ckfinder/userfiles/' . $subFolder . '/' . $fileName);
    }
})->name('admin.upload')->middleware(['auth']);
