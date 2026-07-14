<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use App\Models\AcademicYear;

class ShareAcademicYear
{
    public function handle(Request $request, Closure $next)
    {
        $activeYear = AcademicYear::where('is_active', true)->first();
        if (!$activeYear) {
             // Optional: Create a default one if none exists?
             // For now, let's just null or handle in view
        }
        View::share('activeYear', $activeYear);
        return $next($request);
    }
}
