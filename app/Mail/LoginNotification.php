<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class LoginNotification extends Mailable
{
    use Queueable, SerializesModels;

    public User $user;
    public string $loginTime;
    public string $ipAddress;
    public string $userAgent;
    public string $location;

    public function __construct(User $user, array $loginDetails)
    {
        $this->user = $user;
        $this->loginTime = $loginDetails['time'];
        $this->ipAddress = $loginDetails['ip'];
        $this->userAgent = $loginDetails['user_agent'];
        $this->location = $loginDetails['location'] ?? 'Unknown';
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'New Login to Your ShopEase Account',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.login-notification',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
