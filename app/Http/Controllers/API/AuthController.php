<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Ambil role 'user'
        $userRole = Role::where('name', 'user')->first();

        if (!$userRole) {
            return response()->json(['error' => 'Default user role not found'], 500);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role_id' => $userRole->id,
        ]);

        return response()->json([
            'message' => 'Registrasi Berhasil!',
            'user' => $user,
        ], 201);
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        try {
            if (! $token = JWTAuth::attempt($credentials)) {
                return response()->json(['error' => 'Invalid credentials'], 401);
            }
        } catch (JWTException $e) {
            return response()->json(['error' => 'Gagal membuat token'], 500);
        }

        // Ambil user yang sedang login
        $user = JWTAuth::user();

        // Load relasi role
        $user->load('role');

        return response()->json([
            'token' => $token,
            'user' => $user
        ]);
    }

    public function me(Request $request)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();

            // Muat relasi role
            $user->load('role');

            return response()->json([
                'message' => 'berhasil get user',
                'user' => $user,
            ]);
        } catch (JWTException $e) {
            return response()->json(['error' => 'Token tidak valid'], 401);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Terjadi kesalahan'], 500);
        }
    }
    
    public function logout(Request $request)
    {
        try {
            $token = JWTAuth::getToken();

            if (!$token) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'No token provided'
                ], 401);
            }

            JWTAuth::invalidate($token);

            return response()->json([
                'status' => 'success',
                'message' => 'Logout Berhasil'
            ]);
        } catch (JWTException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to logout, please try again.'
            ], 500);
        }
    }
}
