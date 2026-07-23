<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PasswordChangedMail extends Mailable
{
    use Queueable, SerializesModels;

    public User $user;
    public string $agencyName;
    public ?string $agencyLogo;
    public string $ipAddress;

    public function __construct(User $user, string $ipAddress = '')
    {
        $this->user = $user;
        $this->ipAddress = $ipAddress ?: request()->ip();

        $this->agencyName = (function_exists('tenant') && tenant() && tenant('name')) 
            ? tenant('name') 
            : (\App\Models\Setting::get('agency_name') ?: 'Insurio Agency');

        $this->agencyLogo = (function_exists('tenant') && tenant() && tenant('logo_path')) 
            ? asset('storage/' . tenant('logo_path')) 
            : null;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "Alerte Sécurité : Modification de votre mot de passe - {$this->agencyName}",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.password-changed',
        );
    }
}
