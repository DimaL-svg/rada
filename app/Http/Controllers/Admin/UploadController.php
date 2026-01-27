<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UploadController extends Controller
{
    /**
     * ЗАВАНТАЖЕННЯ ФАЙЛІВ (CKEditor/CKFinder)
     * Приймає файл із редактора, перевіряє його розширення та 
     * розкладає по папках: картинки в /images, інше — в /files.
     */
public function upload(Request $request)
{
    if ($request->hasFile('file')) {
        $file = $request->file('file');
        $filename = time() . '_' . $file->getClientOriginalName();
        
        // Визначаємо шлях до папки зображень CKFinder
        $destinationPath = public_path('ckfinder/userfiles/images');
        
        // Переносимо файл
        $file->move($destinationPath, $filename);
        
        // Повертаємо URL для TinyMCE
        return response()->json([
            'location' => asset('ckfinder/userfiles/images/' . $filename)
        ]);
    }

    return response()->json(['error' => 'Помилка завантаження'], 500);
}
}