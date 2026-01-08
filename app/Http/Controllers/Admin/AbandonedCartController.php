<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AbandonedCart;
use App\Services\AbandonedCartService;
use Illuminate\Http\Request;

class AbandonedCartController extends Controller
{
    public function __construct(protected AbandonedCartService $abandonedCartService)
    {
    }

    public function index(Request $request)
    {
        $stats = $this->abandonedCartService->getStats();
        
        $carts = $this->abandonedCartService->getAbandonedCarts([
            'status' => $request->status,
            'search' => $request->search,
            'date_from' => $request->date_from,
            'date_to' => $request->date_to,
        ]);

        return view('admin.abandoned-carts.index', compact('stats', 'carts'));
    }

    public function show(AbandonedCart $abandonedCart)
    {
        $abandonedCart->load(['user', 'reminders', 'recoveredOrder']);

        return view('admin.abandoned-carts.show', compact('abandonedCart'));
    }

    public function sendReminder(AbandonedCart $abandonedCart)
    {
        if (!$abandonedCart->canSendReminder()) {
            return back()->with('error', 'Cannot send reminder at this time.');
        }

        try {
            \Mail::to($abandonedCart->user->email)
                ->send(new \App\Mail\AbandonedCartReminderMail($abandonedCart));
            
            $abandonedCart->recordReminderSent('email');
            
            return back()->with('success', 'Reminder sent successfully!');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to send reminder: ' . $e->getMessage());
        }
    }

    public function sendBulkReminders()
    {
        $stats = $this->abandonedCartService->sendReminders();

        return back()->with('success', "Sent {$stats['sent']} reminders. {$stats['expired']} carts expired.");
    }

    public function settings()
    {
        $settings = [
            'max_reminders' => config('abandoned_cart.max_reminders', 3),
            'hours_between_reminders' => config('abandoned_cart.hours_between', 24),
            'expiry_days' => config('abandoned_cart.expiry_days', 7),
            'first_reminder_delay' => config('abandoned_cart.first_reminder_delay', 1),
        ];

        return view('admin.abandoned-carts.settings', compact('settings'));
    }
}
