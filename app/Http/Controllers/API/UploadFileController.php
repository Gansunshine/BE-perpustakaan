<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use App\Models\Book;


class UploadFileController extends Controller
{
    public function uploadFile(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $uploadedFileUrl = Cloudinary::upload($request->file('image')->getRealPath())->getSecurePath();

        // Book::create([
        //     "title" => $request->input("image"),
        //     "image" => $uploadedFileUrl
        // ]);

        return response()->json([
            'url' => $uploadedFileUrl,
        ]);
    }

}
