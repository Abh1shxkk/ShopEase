<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    public function index()
    {
        return view('admin.profile.index');
    }

    public function update(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'phone' => 'nullable|string|max:20',
            'avatar' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'avatar_url' => 'nullable|url|max:500',
        ]);

        // Handle avatar - URL takes priority, then file upload
        if (!empty($validated['avatar_url'])) {
            // Delete old local file if exists
            if ($user->avatar && !str_starts_with($user->avatar, 'http')) {
                Storage::disk('public')->delete($user->avatar);
            }
            $validated['avatar'] = $validated['avatar_url'];
        } elseif ($request->hasFile('avatar')) {
            if ($user->avatar && !str_starts_with($user->avatar, 'http')) {
                Storage::disk('public')->delete($user->avatar);
            }
            $validated['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }
        unset($validated['avatar_url']);

        $user->update($validated);

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Profile updated successfully!']);
        }
        return back()->with('success', 'Profile updated successfully!');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|min:8|confirmed',
        ]);

        $user = auth()->user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->with('error', 'Current password is incorrect.');
        }

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return back()->with('success', 'Password updated successfully!');
    }

    public function toggleDarkMode(Request $request)
    {
        $user = auth()->user();
        $user->update(['dark_mode' => $request->boolean('dark_mode')]);
        
        return response()->json(['success' => true]);
    }
}
