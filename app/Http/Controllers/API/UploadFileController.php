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
            'title' => 'required|string|max:255',
            'summary' => 'nullable|string',
            'stok' => 'required|integer',
            'category_id' => 'required|exists:categories,id'
        ]);
    
        $uploadedFileUrl = Cloudinary::upload($request->file('image')->getRealPath())->getSecurePath();
    
        $book = Book::create([
            "title" => $request->input("title"),
            "summary" => $request->input("summary"),
            "image" => $uploadedFileUrl,
            "stok" => $request->input("stok"),
            "category_id" => $request->input("category_id")
        ]);
    
        return response()->json([
            'book' => $book,
        ]);
    }    

}
