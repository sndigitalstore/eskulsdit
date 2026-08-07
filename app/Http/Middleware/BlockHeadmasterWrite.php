<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class BlockHeadmasterWrite
{
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check() && auth()->user()->role === 'headmaster' && !$request->isMethod('get')) {
            abort(403, 'Akses ditolak. Kepala Sekolah hanya diperbolehkan membaca data (Read-Only).');
        }
        return $next($request);
    }
}
