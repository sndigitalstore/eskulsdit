<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserHasRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  ...$roles
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        if (!$request->user()) {
            return redirect()->route('login');
        }

        $userRole = $request->user()->role;
        // Normalisasi peran 'guru' menjadi 'teacher' untuk kompatibilitas
        if ($userRole === 'guru') {
            $userRole = 'teacher';
        }

        $allowedRoles = array_map(fn($r) => $r === 'guru' ? 'teacher' : $r, $roles);

        if (!in_array($userRole, $allowedRoles)) {
            abort(403, 'Akses ditolak. Anda tidak memiliki izin untuk mengakses halaman ini.');
        }

        return $next($request);
    }
}
