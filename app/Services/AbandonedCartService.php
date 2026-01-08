<?php

namespace App\Services;

use App\Mail\AbandonedCartReminderMail;
use App\Models\AbandonedCart;
use App\Models\Cart;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

class AbandonedCartService
{
    protected int $maxReminders = 3;
    protected int $hoursBetweenReminders = 24;
    protected int $expiryDays = 7;

    /**
     * Capture abandoned cart for user
     */
    public function capture(User $user): ?AbandonedCart
    {
        return AbandonedCart::captureForUser($user);
    }

    /**
     * Mark cart as recovered when order is placed
     */
    public function markRecovered(User $user, int $orderId): void
    {
        AbandonedCart::where('user_id', $user->id)
            ->whereIn('status', ['pending', 'reminded'])
            ->get()
            ->each(fn($cart) => $cart->markRecovered($orderId));
    }

    /**
     * Process recovery link click
     */
    public function processRecoveryClick(string $token): ?AbandonedCart
    {
        $abandonedCart = AbandonedCart::where('recovery_token', $token)->first();
        
        if (!$abandonedCart) {
            return null;
        }

        // Mark latest reminder as clicked
        $abandonedCart->reminders()
            ->latest()
            ->first()
            ?->markClicked();

        return $abandonedCart;
    }

    /**
     * Restore cart from abandoned cart
     */
    public function restoreCart(AbandonedCart $abandonedCart): bool
    {
        if (!$abandonedCart->cart_snapshot) {
            return false;
        }

        $user = $abandonedCart->user;
        
        // Clear current cart
        Cart::where('user_id', $user->id)->delete();

        // Restore items from snapshot
        foreach ($abandonedCart->cart_snapshot as $item) {
            Cart::create([
                'user_id' => $user->id,
                'product_id' => $item['product_id'],
                'quantity' => $item['quantity'],
            ]);
        }

        return true;
    }

    /**
     * Send reminder emails for abandoned carts
     */
    public function sendReminders(): array
    {
        $stats = ['sent' => 0, 'failed' => 0, 'expired' => 0];

        // Expire old carts
        $expired = AbandonedCart::where('status', 'pending')
            ->where('created_at', '<', now()->subDays($this->expiryDays))
            ->get();
        
        foreach ($expired as $cart) {
            $cart->markExpired();
            $stats['expired']++;
        }

        // Get carts needing reminders
        $carts = AbandonedCart::needsReminder($this->maxReminders)
            ->with('user')
            ->get();

        foreach ($carts as $cart) {
            if (!$cart->canSendReminder($this->maxReminders, $this->hoursBetweenReminders)) {
                continue;
            }

            try {
                Mail::to($cart->user->email)->send(new AbandonedCartReminderMail($cart));
                $cart->recordReminderSent('email');
                $stats['sent']++;
            } catch (\Exception $e) {
                \Log::error('Failed to send abandoned cart reminder: ' . $e->getMessage());
                $stats['failed']++;
            }
        }

        return $stats;
    }

    /**
     * Get stats for admin
     */
    public function getStats(): array
    {
        $totalAbandoned = AbandonedCart::count();
        $recovered = AbandonedCart::where('status', 'recovered')->count();
        $pending = AbandonedCart::where('status', 'pending')->count();
        $reminded = AbandonedCart::where('status', 'reminded')->count();
        
        $totalValue = AbandonedCart::whereIn('status', ['pending', 'reminded'])->sum('cart_total');
        $recoveredValue = AbandonedCart::where('status', 'recovered')->sum('cart_total');

        return [
            'total_abandoned' => $totalAbandoned,
            'recovered' => $recovered,
            'pending' => $pending,
            'reminded' => $reminded,
            'recovery_rate' => $totalAbandoned > 0 ? round(($recovered / $totalAbandoned) * 100, 1) : 0,
            'total_abandoned_value' => $totalValue,
            'recovered_value' => $recoveredValue,
            'potential_recovery' => $totalValue,
        ];
    }

    /**
     * Get abandoned carts for admin
     */
    public function getAbandonedCarts(array $filters = [])
    {
        $query = AbandonedCart::with(['user', 'reminders']);

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['search'])) {
            $query->whereHas('user', fn($q) => 
                $q->where('name', 'like', "%{$filters['search']}%")
                  ->orWhere('email', 'like', "%{$filters['search']}%")
            );
        }

        if (!empty($filters['date_from'])) {
            $query->whereDate('created_at', '>=', $filters['date_from']);
        }

        if (!empty($filters['date_to'])) {
            $query->whereDate('created_at', '<=', $filters['date_to']);
        }

        return $query->latest()->paginate(20);
    }

    /**
     * Send scheduled reminders for carts abandoned X hours ago
     */
    public function sendScheduledReminders(int $hoursAgo = 1): array
    {
        $stats = ['sent' => 0, 'failed' => 0];

        // Get abandoned carts that are at least X hours old and haven't been reminded yet
        $carts = AbandonedCart::where('status', 'pending')
            ->where('updated_at', '<=', now()->subHours($hoursAgo))
            ->whereDoesntHave('reminders')
            ->with('user')
            ->get();

        foreach ($carts as $cart) {
            if (!$cart->user || !$cart->user->email) {
                continue;
            }

            try {
                Mail::to($cart->user->email)->send(new AbandonedCartReminderMail($cart));
                $cart->recordReminderSent('email');
                $stats['sent']++;
            } catch (\Exception $e) {
                \Log::error('Failed to send abandoned cart reminder: ' . $e->getMessage());
                $stats['failed']++;
            }
        }

        return $stats;
    }
}
