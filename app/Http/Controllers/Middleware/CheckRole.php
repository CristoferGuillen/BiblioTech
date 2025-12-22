<?php

namespace App\Http\Controllers\Middleware;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Closure;

class CheckRole extends Controller
{
    public function handle(Request $request, Closure $next, string $role)
    {
        if (!$request->user()) {
            return redirect('login');
        }

        $rolesArray = explode(',', $role);

        if (!in_array($request->user()->role, $rolesArray)) {
            return redirect('unauthorized');
        }

        return $next($request);
    }
}
