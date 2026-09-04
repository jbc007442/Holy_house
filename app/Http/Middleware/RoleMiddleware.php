<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // User must be logged in
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        // Check user's role
        if (!in_array(auth()->user()->role, $roles, true)) {
            abort(403);
        }

        return $next($request);
    }
}