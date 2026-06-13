<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        // Jika bukan admin, tolak akses (munculkan error 403 Forbidden)
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Akses ditolak. Anda bukan Admin.');
        }

        return $next($request);
    }
}
