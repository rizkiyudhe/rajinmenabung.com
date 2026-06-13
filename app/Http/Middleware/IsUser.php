<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsUser
{
    public function handle(Request $request, Closure $next): Response
    {
        // Jika yang login BUKAN user biasa, tolak aksesnya!
        if (auth()->user()->role !== 'user') {
            abort(403, 'Akses ditolak. Halaman ini khusus untuk Anggota Keluarga (User).');
        }

        return $next($request);
    }
}
