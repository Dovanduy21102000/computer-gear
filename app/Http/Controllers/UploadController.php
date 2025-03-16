<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UploadController extends Controller
{
    public function upload(Request $request)
    {
        // Validate the uploaded file
        $request->validate([
            'upload' => 'required|mimes:jpg,jpeg,png,gif|max:2048' // 2MB max
        ]);

        // Store the file in the "public/uploads" directory
        $file = $request->file('upload');
        $filename = time() . '_' . $file->getClientOriginalName();
        $file->storeAs('public/uploads', $filename);

        // Return the URL for CKEditor to insert the image
        return response()->json([
            'url' => asset('storage/uploads/' . $filename)
        ]);
    }
}
