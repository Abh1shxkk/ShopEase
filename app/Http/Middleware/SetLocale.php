<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    protected array $supportedLocales = ['en', 'hi'];

    public function handle(Request $request, Closure $next): Response
    {
        // Check for locale in session, cookie, or use default
        $locale = Session::get('locale', $request->cookie('locale', config('app.locale')));
        
        // Validate locale
        if (!in_array($locale, $this->supportedLocales)) {
            $locale = config('app.locale');
        }

        App::setLocale($locale);

        return $next($request);
    }
}
