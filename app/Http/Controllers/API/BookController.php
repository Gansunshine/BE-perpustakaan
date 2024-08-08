<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BookController extends Controller
{
    public function dashboard(){
        $limitBook = Book::orderBy('created_at')->take(12)->get();;
        return response()->json([
            'message' => 'Tampil Limit Book berhasil',
            'data' => $limitBook
        ], 200);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $books = Book::all();
        return response()->json([
            'message' => 'Tampil data berhasil',
            'data' => $books
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validasi data yang diterima
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'summary' => 'required|string',
            'stok' => 'required|integer',
            'category_id' => 'required|uuid',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
    
        // Jika file gambar diinput
        if ($request->hasFile('image')) {
            // Unggah gambar ke Cloudinary
            $uploadedFileUrl = Cloudinary::upload($request->file('image')->getRealPath())->getSecurePath();
    
            // Mengganti nilai request image menjadi URL Cloudinary yang baru
            $validatedData['image'] = $uploadedFileUrl;
        }
    
        // Buat buku baru
        $book = Book::create($validatedData);
    
        // Kembalikan respon JSON
        return response()->json([
            'message' => 'Book created successfully',
            'book' => $book,
        ], 201);
    }    

    /**
     * Display the specified resource.
     */
    public function show( string $id)
    {
        $book = Book::findOrFail($id);
        return response()->json([
            'message' => 'detail Book success',
            'data' => $book
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Validasi data yang diterima
        $validatedData = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'summary' => 'sometimes|required|string',
            'stok' => 'sometimes|required|integer',
            'category_id' => 'sometimes|required|uuid|exists:categories,id',
            'image' => 'sometimes|required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
    
        $book = Book::findOrFail($id);
    
        // Jika file gambar diinput
        if ($request->hasFile('image')) {
            // Hapus gambar lama dari Cloudinary jika perlu
            // Misal, Anda perlu menyimpan public_id saat mengunggah untuk menghapusnya
            Cloudinary::destroy($book->cloudinary_public_id);
    
            // Unggah gambar baru ke Cloudinary
            $uploadedFileUrl = Cloudinary::upload($request->file('image')->getRealPath())->getSecurePath();
    
            // Mengganti nilai request image menjadi URL Cloudinary yang baru
            $validatedData['image'] = $uploadedFileUrl;
        }
    
        // Perbarui buku
        $book->update($validatedData);
    
        // Kembalikan respon JSON
        return response()->json([
            'message' => 'Book updated successfully',
            'book' => $book,
        ]);
    }    

    /**
     * Remove the specified resource from storage.
     */
    public function destroy( string $id)
    {
        $book = Book::findOrFail($id);

        // Hapus gambar
        if ($book->image) {
            $oldImage = str_replace(env('APP_URL') . '/storage/images/', '', $book->image);
            Storage::disk('public')->delete('images/' . $oldImage);
        }

        // Hapus buku
        $book->delete();

        // Kembalikan respon JSON
        return response()->json([
            'message' => 'Book deleted successfully',
        ]);
    }
}
