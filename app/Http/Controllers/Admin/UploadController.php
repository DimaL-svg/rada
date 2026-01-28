<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UploadController extends Controller
{
    /**
     * ЗАВАНТАЖЕННЯ ФАЙЛІВ (CKEditor/CKFinder)
     */
public function upload(Request $request)
{
    if ($request->hasFile('file')) {
        $file = $request->file('file');
        $filename = time() . '_' . $file->getClientOriginalName();
        $destinationPath = public_path('ckfinder/userfiles/images');
        $file->move($destinationPath, $filename);
        return response()->json([
            'location' => asset('ckfinder/userfiles/images/' . $filename)
        ]);
    }
    return response()->json(['error' => 'Помилка завантаження'], 500);
}
}