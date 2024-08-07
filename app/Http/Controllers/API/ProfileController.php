<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Profile;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validasi data yang diterima
        $validatedData = $request->validate([
            'bio' => 'required|string|max:255',
            'age' => 'required|integer',
        ]);

        // Ambil user yang sedang login
        $user = Auth::user();

        // Tambahkan user_id ke data yang divalidasi
        $validatedData['user_id'] = $user->id;

        // Buat atau perbarui data profile
        $profile = Profile::updateOrCreate(
            ['user_id' => $user->id],
            $validatedData
        );

        return response()->json([
            'message' => 'Profil berhasil dibuat/diubah',
            'data' => $profile
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
