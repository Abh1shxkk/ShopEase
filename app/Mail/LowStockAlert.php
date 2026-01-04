<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;

class LowStockAlert extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Collection $lowStockProducts,
        public Collection $outOfStockProducts
    ) {}

    public function envelope(): Envelope
    {
        $count = $this->lowStockProducts->count() + $this->outOfStockProducts->count();
        return new Envelope(
            subject: "⚠️ Inventory Alert: {$count} products need attention",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.low-stock-alert',
        );
    }
}
