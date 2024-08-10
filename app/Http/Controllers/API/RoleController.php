<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Role;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $role = Role::orderBy('created_at', 'desc')->get();
        return response()->json([
            'message' => 'Tampil data Role berhasil',
            'data' => $role
        ], 200);
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

        // Buat role baru
        $role = Role::create($validatedData);

        return response()->json([
            'message' => 'Role berhasil dibuat',
            'data' => $role
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
        // Validasi data yang diterima
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        // Cari role berdasarkan ID
        $role = Role::find($id);

        if (!$role) {
            return response()->json([
                'message' => "Role dengan ID $id tidak ditemukan"
            ], 404);
        }

        // Perbarui role
        $role->update($validatedData);

        return response()->json([
            'message' => 'Role berhasil diubah',
            'data' => $role
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy( string $id)
    {
        // Cari role berdasarkan ID
        $role = Role::find($id);

        if (!$role) {
            return response()->json([
                'message' => "Role dengan ID $id tidak ditemukan"
            ], 404);
        }

        // Hapus role
        $role->delete();

        return response()->json([
            'message' => 'Role berhasil dihapus'
        ], 200);
    }
}
