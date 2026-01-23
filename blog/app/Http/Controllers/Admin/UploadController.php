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
        // Перевіряємо, чи прийшов файл у запиті
        if ($request->hasFile('upload')) {
            $file = $request->file('upload');
            $extension = strtolower($file->getClientOriginalExtension());
            
            // Створюємо унікальну назву, щоб файли не перезаписували один одного
            $fileName = time() . '_' . $file->getClientOriginalName();
            
            // Розумне сортування: картинки окремо, документи окремо
            $subFolder = in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp']) ? 'images' : 'files';
            
            // Шлях, куди фізично покладемо файл на сервері
            $destinationPath = public_path('ckfinder/userfiles/' . $subFolder);
            
            // Переміщуємо файл у вказану папку
            $file->move($destinationPath, $fileName);
            
            // Повертаємо пряме посилання на файл для вставки у статтю
            return asset('ckfinder/userfiles/' . $subFolder . '/' . $fileName);
        }
    }
}