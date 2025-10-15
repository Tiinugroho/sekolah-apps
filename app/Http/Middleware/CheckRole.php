<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string ...$roles  Parameter ini akan menangkap semua role yang dikirim (misal: 'kepala_sekolah', 'tata_usaha')
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // 1. Pastikan pengguna sudah login.
        //    Meskipun biasanya sudah ditangani oleh middleware 'auth',
        //    ini adalah lapisan keamanan tambahan yang baik.
        if (!Auth::check()) {
            return redirect('login');
        }

        // 2. Ambil role dari pengguna yang sedang login.
        $userRole = $request->user()->role;

        // 3. Periksa apakah role pengguna ada di dalam daftar role yang diizinkan ($roles).
        if (in_array($userRole, $roles)) {
            // 4. Jika diizinkan, lanjutkan permintaan ke controller.
            return $next($request);
        }

        // 5. Jika tidak diizinkan, hentikan permintaan dan tampilkan halaman error 403 (Forbidden).
        abort(403, 'AKSES DITOLAK. ANDA TIDAK MEMILIKI WEWENANG UNTUK MENGAKSES HALAMAN INI.');
    }
}