<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if (!$request->user() || $request->user()->role !== $role) {
            if ($request->user()) {
                return $request->user()->role === 'admin'
                    ? redirect('/admin')
                    : redirect('/dashboard');
            }
            return redirect('/login');
        }

        return $next($request);
    }
}
