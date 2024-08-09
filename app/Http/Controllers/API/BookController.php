<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Book;
use Illuminate\Http\Request;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class BookController extends Controller
{
    public function dashboard()
    {
        $limitBook = Book::orderBy('created_at', 'desc')->take(12)->get();
        return response()->json([
            'message' => 'Tampil Limit Book berhasil',
            'data' => $limitBook
        ], 200);
    }

    public function index()
    {
        $books = Book::all();
        return response()->json([
            'message' => 'Tampil data berhasil',
            'data' => $books
        ], 200);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'summary' => 'required|string',
            'stok' => 'required|integer',
            'category_id' => 'required|exists:categories,id',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $result = Cloudinary::upload($request->file('image')->getRealPath());
            $validatedData['image'] = $result->getSecurePath();
            $validatedData['cloudinary_public_id'] = $result->getPublicId();
        }

        $book = Book::create($validatedData);

        return response()->json([
            'message' => 'Book created successfully',
            'book' => $book,
        ], 201);
    }

    public function show(string $id)
    {
        $book = Book::findOrFail($id);
        return response()->json([
            'message' => 'detail Book success',
            'data' => $book
        ], 200);
    }

    public function update(Request $request, string $id)
    {
        $validatedData = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'summary' => 'sometimes|required|string',
            'stok' => 'sometimes|required|integer',
            'category_id' => 'sometimes|required|exists:categories,id',
            'image' => 'sometimes|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $book = Book::findOrFail($id);

        if ($request->hasFile('image')) {
            if ($book->cloudinary_public_id) {
                Cloudinary::destroy($book->cloudinary_public_id);
            }

            $result = Cloudinary::upload($request->file('image')->getRealPath());
            $validatedData['image'] = $result->getSecurePath();
            $validatedData['cloudinary_public_id'] = $result->getPublicId();
        }

        $book->update($validatedData);

        return response()->json([
            'message' => 'Book updated successfully',
            'book' => $book,
        ]);
    }

    public function destroy(string $id)
    {
        $book = Book::findOrFail($id);

        if ($book->cloudinary_public_id) {
            Cloudinary::destroy($book->cloudinary_public_id);
        }

        $book->delete();

        return response()->json([
            'message' => 'Book deleted successfully',
        ]);
    }
}
