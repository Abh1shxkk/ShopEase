<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $addresses = $user->addresses()->orderBy('is_default', 'desc')->get();
        $paymentMethods = $user->paymentMethods()->orderBy('is_default', 'desc')->get();
        
        return view('profile.index', compact('addresses', 'paymentMethods'));
    }

    public function update(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'phone' => 'nullable|string|max:15',
            'date_of_birth' => 'nullable|date|before:today',
            'gender' => 'nullable|in:male,female,other',
            'avatar' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->date_of_birth = $request->date_of_birth;
        $user->gender = $request->gender;

        if ($request->hasFile('avatar')) {
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }
            $user->avatar = $request->file('avatar')->store('avatars', 'public');
        }

        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Profile updated successfully!',
            'avatar_url' => $user->avatar_url
        ]);
    }

    public function updatePassword(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'current_password' => 'required',
            'password' => 'required|min:8|confirmed',
        ]);

        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Current password is incorrect'
            ], 422);
        }

        $user->password = Hash::make($request->password);
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Password changed successfully!'
        ]);
    }

    public function updatePreferences(Request $request)
    {
        $user = auth()->user();

        $user->email_notifications = $request->boolean('email_notifications');
        $user->sms_notifications = $request->boolean('sms_notifications');
        $user->marketing_emails = $request->boolean('marketing_emails');
        $user->dark_mode = $request->boolean('dark_mode');
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Preferences updated successfully!'
        ]);
    }

    public function toggleDarkMode(Request $request)
    {
        $user = auth()->user();
        $user->update(['dark_mode' => $request->boolean('dark_mode')]);
        
        return response()->json(['success' => true]);
    }

    public function storeAddress(Request $request)
    {
        $request->validate([
            'label' => 'required|string|max:50',
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:15',
            'address_line_1' => 'required|string|max:500',
            'address_line_2' => 'nullable|string|max:500',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'pincode' => 'required|string|max:10',
            'landmark' => 'nullable|string|max:255',
            'type' => 'required|in:shipping,billing,both',
            'is_default' => 'boolean',
        ]);

        $user = auth()->user();

        if ($request->boolean('is_default')) {
            $user->addresses()->update(['is_default' => false]);
        }

        $address = $user->addresses()->create($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Address added successfully!',
            'address' => [
                'id' => $address->id,
                'label' => $address->label,
                'name' => $address->name,
                'phone' => $address->phone,
                'full_address' => $address->full_address,
                'is_default' => $address->is_default,
                'type' => $address->type,
            ]
        ]);
    }

    public function updateAddress(Request $request, Address $address)
    {
        if ($address->user_id !== auth()->id()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'label' => 'required|string|max:50',
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:15',
            'address_line_1' => 'required|string|max:500',
            'address_line_2' => 'nullable|string|max:500',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'pincode' => 'required|string|max:10',
            'landmark' => 'nullable|string|max:255',
            'type' => 'required|in:shipping,billing,both',
            'is_default' => 'boolean',
        ]);

        if ($request->boolean('is_default')) {
            auth()->user()->addresses()->where('id', '!=', $address->id)->update(['is_default' => false]);
        }

        $address->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Address updated successfully!'
        ]);
    }

    public function deleteAddress(Address $address)
    {
        if ($address->user_id !== auth()->id()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $address->delete();

        return response()->json([
            'success' => true,
            'message' => 'Address deleted successfully!'
        ]);
    }

    public function storePaymentMethod(Request $request)
    {
        $request->validate([
            'type' => 'required|in:card,upi,netbanking,wallet',
            'label' => 'nullable|string|max:100',
            'upi_id' => 'required_if:type,upi|nullable|string|max:100',
            'bank_name' => 'required_if:type,netbanking|nullable|string|max:100',
            'is_default' => 'boolean',
        ]);

        $user = auth()->user();

        if ($request->boolean('is_default')) {
            $user->paymentMethods()->update(['is_default' => false]);
        }

        $method = $user->paymentMethods()->create($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Payment method added successfully!',
            'payment_method' => [
                'id' => $method->id,
                'type' => $method->type,
                'display_name' => $method->display_name,
                'label' => $method->label,
                'is_default' => $method->is_default,
            ]
        ]);
    }

    public function deletePaymentMethod(PaymentMethod $paymentMethod)
    {
        if ($paymentMethod->user_id !== auth()->id()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $paymentMethod->delete();

        return response()->json([
            'success' => true,
            'message' => 'Payment method removed successfully!'
        ]);
    }

    public function deleteAccount(Request $request)
    {
        $request->validate([
            'password' => 'required',
        ]);

        $user = auth()->user();

        if (!Hash::check($request->password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Password is incorrect'
            ], 422);
        }

        if ($user->avatar) {
            Storage::disk('public')->delete($user->avatar);
        }

        auth()->logout();
        $user->delete();

        return response()->json([
            'success' => true,
            'message' => 'Your account has been deleted.',
            'redirect' => '/'
        ]);
    }
}
