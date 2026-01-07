<?php

namespace App\Http\Controllers;

use App\Models\NewsletterSubscriber;
use Illuminate\Http\Request;

class NewsletterController extends Controller
{
    public function subscribe(Request $request)
    {
        $request->validate([
            'email' => 'required|email|max:255',
        ]);

        $email = strtolower($request->email);
        
        $existing = NewsletterSubscriber::where('email', $email)->first();
        
        if ($existing) {
            if ($existing->status === 'unsubscribed') {
                $existing->resubscribe();
                return response()->json([
                    'success' => true,
                    'message' => __('messages.newsletter.resubscribed')
                ]);
            }
            
            return response()->json([
                'success' => false,
                'message' => __('messages.newsletter.already_subscribed')
            ], 422);
        }

        NewsletterSubscriber::create([
            'email' => $email,
            'name' => $request->name,
        ]);

        return response()->json([
            'success' => true,
            'message' => __('messages.newsletter.subscribed')
        ]);
    }

    public function unsubscribe(Request $request, $token)
    {
        $subscriber = NewsletterSubscriber::where('unsubscribe_token', $token)->first();
        
        if (!$subscriber) {
            return redirect()->route('home')->with('error', __('messages.newsletter.invalid_token'));
        }

        $subscriber->unsubscribe();

        return view('newsletter.unsubscribed', compact('subscriber'));
    }
}
