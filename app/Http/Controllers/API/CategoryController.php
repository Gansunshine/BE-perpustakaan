<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;


class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::all();
        return response()->json($categories);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validasi data yang diterima
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        // Buat kategori baru
        $category = Category::create([
            'name' => $validatedData['name'],
        ]);

        // Kembalikan respon JSON
        return response()->json([
            'message' => 'Category created successfully',
        ], 201);
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $category = Category::with('books')->find($id);
        if ($category) {
            return response()->json($category);
        }
        return response()->json(['message' => 'Category not found'], 404);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $category = Category::find($id);
        if ($category) {
            $category->update([
                'name' => $validatedData['name'],
            ]);
            return response()->json([
                'message' => 'Category updated successfully',
            ]);
        }
        return response()->json(['message' => 'Category not found'], 404);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $category = Category::find($id);
        if ($category) {
            $category->delete();
            return response()->json(['message' => 'Category deleted successfully']);
        }
        return response()->json(['message' => 'Category not found'], 404);
    }
}
