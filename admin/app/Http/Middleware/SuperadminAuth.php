<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SuperadminAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if admin is logged in and role is superadmin
        if (!$request->session()->has('admin_id') || $request->session()->get('admin_role') !== 'superadmin') {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda tidak memiliki akses sebagai Superadmin.'
                ], 403);
            }

            return redirect()->route('admin.dashboard')
                ->with('error', 'Akses ditolak. Halaman tersebut khusus untuk Superadmin.');
        }

        return $next($request);
    }
}
