<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class LanguageController extends Controller
{
    protected array $supportedLocales = [
        'en' => ['name' => 'English', 'native' => 'English', 'flag' => '🇬🇧'],
        'hi' => ['name' => 'Hindi', 'native' => 'हिंदी', 'flag' => '🇮🇳'],
    ];

    public function switch(Request $request, string $locale)
    {
        if (!array_key_exists($locale, $this->supportedLocales)) {
            $locale = 'en';
        }

        Session::put('locale', $locale);

        return redirect()->back()->withCookie(cookie('locale', $locale, 60 * 24 * 365));
    }

    public function getSupportedLocales()
    {
        return response()->json($this->supportedLocales);
    }
}
