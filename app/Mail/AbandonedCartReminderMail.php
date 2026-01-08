<?php

namespace App\Mail;

use App\Models\AbandonedCart;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AbandonedCartReminderMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public AbandonedCart $abandonedCart)
    {
    }

    public function envelope(): Envelope
    {
        $subjects = [
            1 => "You left something behind! 🛒",
            2 => "Your cart misses you! Don't let these items slip away",
            3 => "Last chance! Your cart is about to expire",
        ];

        $subject = $subjects[$this->abandonedCart->reminder_count + 1] ?? $subjects[1];

        return new Envelope(subject: $subject);
    }

    public function content(): Content
    {
        return new Content(view: 'emails.abandoned-cart-reminder');
    }
}
