<?php

namespace App\Mail;

use App\Models\User;
use App\Models\Employe;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class EmployeeInvitationMail extends Mailable
{
    use Queueable, SerializesModels;

    public User $user;
    public Employe $employe;
    public string $activationUrl;
    public string $agencyName;
    public ?string $agencyLogo;

    public function __construct(User $user, Employe $employe, string $activationUrl)
    {
        $this->user = $user;
        $this->employe = $employe;
        $this->activationUrl = $activationUrl;

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
            subject: "Invitation à rejoindre l'équipe {$this->agencyName} - Activation de votre compte",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.employee-invitation',
        );
    }
}
