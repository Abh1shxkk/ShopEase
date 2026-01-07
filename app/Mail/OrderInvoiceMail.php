<?php

namespace App\Mail;

use App\Models\Order;
use App\Services\InvoiceService;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OrderInvoiceMail extends Mailable
{
    use Queueable, SerializesModels;

    public Order $order;
    public string $invoiceNumber;

    public function __construct(Order $order)
    {
        $this->order = $order;
        $this->invoiceNumber = (new InvoiceService())->generateInvoiceNumber($order);
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Your Invoice #' . $this->invoiceNumber . ' - Order Confirmed',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.order-invoice',
        );
    }

    public function attachments(): array
    {
        $invoiceService = new InvoiceService();
        $pdf = $invoiceService->generatePdf($this->order);
        
        return [
            Attachment::fromData(fn () => $pdf->output(), 'invoice-' . $this->order->order_number . '.pdf')
                ->withMime('application/pdf'),
        ];
    }
}
