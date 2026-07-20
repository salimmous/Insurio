<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RenewalReminderMail extends Mailable
{
    use Queueable, SerializesModels;

    public $client;
    public $contrat;
    public $agencyName;
    public $agencyPhone;

    public function __construct($client, $contrat, $agencyName, $agencyPhone)
    {
        $this->client = $client;
        $this->contrat = $contrat;
        $this->agencyName = $agencyName;
        $this->agencyPhone = $agencyPhone;
    }

    public function build()
    {
        $html = "
        <div style='font-family: sans-serif; color: #334155; max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #e2e8f0; border-radius: 12px;'>
            <h2 style='color: #0d9488; margin-bottom: 20px;'>Rappel de renouvellement de votre contrat d'assurance</h2>
            <p>Bonjour <strong>{$this->client->nom} {$this->client->prenom}</strong>,</p>
            <p>Nous vous informons que votre contrat d'assurance automobile numéro <strong>{$this->contrat->numero_contrat}</strong> (Véhicule : {$this->contrat->matricule}) arrive à échéance le <strong>{$this->contrat->date_echeance->format('d/m/Y')}</strong>.</p>
            <p>Afin de continuer à bénéficier de vos garanties en toute sérénité, nous vous invitons à prendre contact avec notre cabinet pour renouveler votre contrat.</p>
            <div style='margin: 30px 0; text-align: center;'>
                <a href='tel:{$this->agencyPhone}' style='background-color: #0d9488; color: #ffffff; padding: 12px 24px; border-radius: 8px; text-decoration: none; font-weight: bold;'>Contacter l'Agence ({$this->agencyPhone})</a>
            </div>
            <p>Nous vous remercions de votre confiance.</p>
            <hr style='border: 0; border-top: 1px solid #e2e8f0; margin: 20px 0;'>
            <p style='font-size: 11px; color: #94a3b8; text-align: center;'>Cet e-mail vous est envoyé par {$this->agencyName} via la plateforme de gestion d'assurances Insurio.</p>
        </div>";

        return $this->subject("Rappel d'échéance : Renouvellement de votre contrat d'assurance - {$this->agencyName}")
            ->html($html);
    }
}
