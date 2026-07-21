<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Http\Request;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class LoginNotificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public string $ip;
    public string $userAgent;
    public string $time;
    public string $lockUrl;

    public function __construct(public User $user, Request $request)
    {
        $this->ip        = $request->ip();
        $this->userAgent = $request->userAgent() ?? 'Unknown';
        $this->time      = now()->format('M d, Y \a\t H:i T');
        $this->lockUrl   = route('admin.security');
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '🔐 New Login Detected — Insurio',
        );
    }

    public function content(): Content
    {
        return new Content(view: 'emails.login-notification');
    }
}
