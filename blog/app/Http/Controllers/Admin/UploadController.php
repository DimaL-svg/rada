<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UploadController extends Controller
{
    public function upload(Request $request)
    {
        if ($request->hasFile('upload')) {
            $file = $request->file('upload');
            $extension = strtolower($file->getClientOriginalExtension());
            $fileName = time() . '_' . $file->getClientOriginalName();
            
            $subFolder = in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp']) ? 'images' : 'files';
            $destinationPath = public_path('ckfinder/userfiles/' . $subFolder);
            
            $file->move($destinationPath, $fileName);
            
            return asset('ckfinder/userfiles/' . $subFolder . '/' . $fileName);
        }
    }
}