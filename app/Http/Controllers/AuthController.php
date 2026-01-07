<?php

namespace App\Http\Controllers;

use App\Mail\LoginNotification;
use App\Mail\LoginOtpMail;
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

        // Check if credentials are valid
        $user = User::where('email', $credentials['email'])->first();
        
        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            return back()->withErrors([
                'email' => 'The provided credentials do not match our records.',
            ])->onlyInput('email');
        }

        // Generate OTP
        $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        
        $user->update([
            'login_otp' => $otp,
            'login_otp_expires_at' => now()->addMinutes(10),
        ]);

        // Store user id and remember preference in session for OTP verification
        session([
            'login_user_id' => $user->id,
            'login_remember' => $request->boolean('remember'),
        ]);

        // In local/dev environment, also store OTP in session for easy testing
        if (app()->environment('local', 'development')) {
            session(['dev_otp' => $otp]);
        }

        // Send OTP email
        Mail::to($user->email)->send(new LoginOtpMail($user, $otp));

        return redirect()->route('login.otp')->with('success', 'OTP sent to your email address.');
    }

    public function showOtpForm()
    {
        if (!session('login_user_id')) {
            return redirect()->route('login');
        }

        $user = User::find(session('login_user_id'));
        
        return view('auth.verify-otp', [
            'email' => $user ? substr($user->email, 0, 3) . '***' . substr($user->email, strpos($user->email, '@')) : '',
        ]);
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp' => 'required|string|size:6',
        ]);

        $userId = session('login_user_id');
        if (!$userId) {
            return redirect()->route('login')->withErrors(['email' => 'Session expired. Please login again.']);
        }

        $user = User::find($userId);
        
        if (!$user) {
            return redirect()->route('login')->withErrors(['email' => 'User not found.']);
        }

        // Check OTP
        if ($user->login_otp !== $request->otp) {
            return back()->withErrors(['otp' => 'Invalid OTP. Please try again.']);
        }

        // Check expiry
        if ($user->login_otp_expires_at < now()) {
            return back()->withErrors(['otp' => 'OTP has expired. Please request a new one.']);
        }

        // Clear OTP
        $user->update([
            'login_otp' => null,
            'login_otp_expires_at' => null,
        ]);

        // Login user
        Auth::login($user, session('login_remember', false));
        
        // Clear session
        session()->forget(['login_user_id', 'login_remember']);
        
        // Regenerate session
        $request->session()->regenerate();
        
        // Send login notification
        $this->sendLoginNotification($user, $request);

        // ALWAYS set flag to show membership popup for testing (remove !$user->isMember() check temporarily)
        if (!$user->hide_membership_popup) {
            session(['show_membership_popup' => true]);
            \Log::info('Membership popup session set for user: ' . $user->id);
        }

        // Redirect based on role
        if ($user->role === 'admin') {
            return redirect()->intended('/admin');
        }
        
        return redirect()->intended('/shop');
    }

    public function resendOtp()
    {
        $userId = session('login_user_id');
        if (!$userId) {
            return response()->json(['error' => 'Session expired'], 400);
        }

        $user = User::find($userId);
        if (!$user) {
            return response()->json(['error' => 'User not found'], 400);
        }

        // Generate new OTP
        $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        
        $user->update([
            'login_otp' => $otp,
            'login_otp_expires_at' => now()->addMinutes(10),
        ]);

        // In local/dev environment, update OTP in session for easy testing
        if (app()->environment('local', 'development')) {
            session(['dev_otp' => $otp]);
        }

        // Send OTP email
        Mail::to($user->email)->send(new LoginOtpMail($user, $otp));

        return response()->json(['success' => true, 'message' => 'OTP resent successfully', 'dev_otp' => app()->environment('local', 'development') ? $otp : null]);
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

        // Show membership popup for new users
        session(['show_membership_popup' => true]);

        return redirect('/shop');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }

    public function hideMembershipPopup(Request $request)
    {
        if (Auth::check()) {
            Auth::user()->update(['hide_membership_popup' => true]);
        }
        session()->forget('show_membership_popup');
        
        return response()->json(['success' => true]);
    }

    public function dismissMembershipPopup()
    {
        session()->forget('show_membership_popup');
        return response()->json(['success' => true]);
    }

    protected function sendLoginNotification(User $user, Request $request): void
    {
        $loginDetails = [
            'time' => now()->format('F j, Y \a\t g:i A'),
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'location' => 'Unknown',
        ];

        Mail::to($user->email)->queue(new LoginNotification($user, $loginDetails));
        $user->update(['last_login_at' => now()]);
    }
}
