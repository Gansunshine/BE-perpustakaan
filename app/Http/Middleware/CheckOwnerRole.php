<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Role;
use Illuminate\Support\Facades\Auth;

class CheckOwnerRole
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();

        if ($user) {
            $userRole = Role::find($user->role_id);
            if ($userRole && $userRole->name === 'owner') {
                return $next($request);
            }
        }

        return response()->json([
            'message' => 'Anda tidak dapat mengakses halaman ADMIN!',
        ], 401);
    }
}
