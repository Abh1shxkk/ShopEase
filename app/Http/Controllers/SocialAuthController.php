<?php

namespace App\Http\Controllers;

use App\Mail\LoginNotification;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class SocialAuthController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
            return $this->handleSocialUser($googleUser, 'google');
        } catch (\Exception $e) {
            return redirect()->route('login')->with('error', 'Google login failed. Please try again.');
        }
    }

    public function redirectToFacebook()
    {
        return Socialite::driver('facebook')->redirect();
    }

    public function handleFacebookCallback()
    {
        try {
            $facebookUser = Socialite::driver('facebook')->user();
            return $this->handleSocialUser($facebookUser, 'facebook');
        } catch (\Exception $e) {
            return redirect()->route('login')->with('error', 'Facebook login failed. Please try again.');
        }
    }

    protected function handleSocialUser($socialUser, $provider)
    {
        $providerId = $provider . '_id';
        
        // Check if user exists with this social ID
        $user = User::where($providerId, $socialUser->getId())->first();
        
        if (!$user) {
            // Check if user exists with same email
            $user = User::where('email', $socialUser->getEmail())->first();
            
            if ($user) {
                // Link social account to existing user
                $user->update([
                    $providerId => $socialUser->getId(),
                    'social_avatar' => $socialUser->getAvatar(),
                ]);
            } else {
                // Create new user
                $user = User::create([
                    'name' => $socialUser->getName(),
                    'email' => $socialUser->getEmail(),
                    'password' => Hash::make(Str::random(24)),
                    $providerId => $socialUser->getId(),
                    'social_avatar' => $socialUser->getAvatar(),
                    'email_verified_at' => now(),
                ]);
            }
        } else {
            // Update avatar if changed
            $user->update(['social_avatar' => $socialUser->getAvatar()]);
        }

        Auth::login($user, true);
        
        // Send login notification email
        $this->sendLoginNotification($user);

        // Redirect based on role
        if ($user->role === 'admin') {
            return redirect()->intended('/admin');
        }

        return redirect()->intended('/shop');
    }

    protected function sendLoginNotification(User $user): void
    {
        $loginDetails = [
            'time' => now()->format('F j, Y \a\t g:i A'),
            'ip' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'location' => 'Unknown',
        ];

        Mail::to($user->email)->queue(new LoginNotification($user, $loginDetails));
        $user->update(['last_login_at' => now()]);
    }
}
