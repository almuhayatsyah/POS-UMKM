<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!auth()->check()) {
            return redirect('login');
        }

        // Jika user adalah ADMIN, beri akses penuh (Opsional, tapi praktis untuk UMKM)
        if (auth()->user()->isAdmin()) {
            return $next($request);
        }

        // Cek apakah peran user ada di dalam daftar role yang diizinkan
        if (in_array(auth()->user()->peran, $roles)) {
            return $next($request);
        }

        abort(403, 'Akses Ditolak. Anda tidak memiliki izin untuk halaman ini.');
    }
}
