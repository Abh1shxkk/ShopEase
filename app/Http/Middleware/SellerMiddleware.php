<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SellerMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (!$user) {
            return redirect()->route('login');
        }

        if (!$user->seller) {
            return redirect()->route('seller.register')
                ->with('info', 'Please register as a seller first.');
        }

        if ($user->seller->isPending()) {
            return redirect()->route('seller.pending');
        }

        if ($user->seller->isSuspended()) {
            return redirect()->route('seller.suspended');
        }

        if (!$user->seller->isApproved()) {
            return redirect()->route('seller.pending');
        }

        return $next($request);
    }
}
