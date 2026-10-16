<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if (!$request->user()) {
            return redirect()->route('login');
        }

        if ($request->user()->role !== $role) {
            // Untuk API request → return JSON 403
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json([
                    'message' => 'Akses ditolak. Anda tidak memiliki izin.',
                ], 403);
            }

            // Untuk web request → abort 403 dengan halaman error
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }

        return $next($request);
    }
}