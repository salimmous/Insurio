<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Http\Request;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SuspiciousLoginMail extends Mailable
{
    use Queueable, SerializesModels;

    public string $ip;
    public string $time;

    public function __construct(public User $user, Request $request)
    {
        $this->ip   = $request->ip();
        $this->time = now()->format('M d, Y \a\t H:i T');
    }

    public function envelope(): Envelope
    {
        return new Envelope(subject: '⚠️ Suspicious Login Detected — Insurio');
    }

    public function content(): Content
    {
        return new Content(view: 'emails.suspicious-login');
    }
}
