<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class UploadController extends Controller
{
    public function binary(Request $request)
    {
        $request->validate([
            'file' => 'image',
        ]);

        $uploadedFile = $request->file('file');
        $pathInfo = pathinfo($uploadedFile->getClientOriginalName());
        $filename = Str::slug($pathInfo['filename'], '-') . '-' . (string)Str::uuid() . '.' . $pathInfo['extension'];
        $resourceDir = 'files/';

        Storage::disk('public')->putFileAs(
            $resourceDir,
            $uploadedFile,
            $filename
        );

        $url_local = env('APP_URL') . Storage::url($resourceDir . $filename);

        return response()->json($url_local);
    }
}
