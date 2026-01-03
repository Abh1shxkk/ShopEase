<?php

namespace App\Http\Controllers;

use App\Mail\LoginNotification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            
            // Send login notification email
            $this->sendLoginNotification(Auth::user(), $request);

            // Admin goes to admin panel, users go to shop (or intended URL like checkout)
            if (Auth::user()->role === 'admin') {
                return redirect()->intended('/admin');
            }
            
            // If user was trying to checkout, redirect there, otherwise go to shop
            return redirect()->intended('/shop');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        Auth::login($user);

        // Redirect to shop after registration
        return redirect('/shop');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }

    protected function sendLoginNotification(User $user, Request $request): void
    {
        $loginDetails = [
            'time' => now()->format('F j, Y \a\t g:i A'),
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'location' => 'Unknown', // Can integrate IP geolocation API later
        ];

        // Send email (queued for better performance)
        Mail::to($user->email)->queue(new LoginNotification($user, $loginDetails));
        
        // Update last login timestamp
        $user->update(['last_login_at' => now()]);
    }
}
