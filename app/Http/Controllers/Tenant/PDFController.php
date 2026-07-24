<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\ContratAuto;
use App\Models\Setting;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class PDFController extends Controller
{
    public function generate(int $contratId, string $type)
    {
        $contrat = ContratAuto::with(['client', 'vehicule', 'compagnie', 'succursale'])->findOrFail($contratId);

        // Get tenant settings for header
        $agencyName = Setting::get('agency_name', tenant('name') ?? 'Insurio Assurance');
        $agencyLogo = tenant('logo_path') ? storage_path('app/public/' . tenant('logo_path')) : Setting::get('agency_logo', '');
        $agencyAddress = Setting::get('agency_address', 'Casablanca, Maroc');
        $agencyPhone = Setting::get('agency_phone', '+212 5 22 00 00 00');
        $agencyEmail = Setting::get('agency_email', 'contact@insurio.com');

        $data = [
            'contrat' => $contrat,
            'type' => $type,
            'agencyName' => $agencyName,
            'agencyLogo' => $agencyLogo,
            'agencyAddress' => $agencyAddress,
            'agencyPhone' => $agencyPhone,
            'agencyEmail' => $agencyEmail,
            'title' => $this->getTitle($type),
        ];

        // Load the blade view based on type
        $pdf = Pdf::loadView('pdf.document', $data);

        return $pdf->download(strtolower($type) . '_' . $contrat->numero_contrat . '.pdf');
    }

    public function generateEmployeePdf(int $employeId)
    {
        $employe = \App\Models\Employe::with(['succursale', 'user'])->findOrFail($employeId);
        $agencyName = Setting::get('agency_name', tenant('name') ?? 'Insurio Agency');
        
        $data = [
            'employe' => $employe,
            'agencyName' => $agencyName,
        ];

        $pdf = Pdf::loadView('pdf.employee-card', $data);
        return $pdf->download('fiche_employe_' . $employe->matricule_employe . '.pdf');
    }

    public function printEmployeeCard(int $employeId)
    {
        $employe = \App\Models\Employe::with(['succursale', 'user'])->findOrFail($employeId);
        $agencyName = Setting::get('agency_name', tenant('name') ?? 'Insurio Agency');

        return view('pdf.employee-card', [
            'employe' => $employe,
            'agencyName' => $agencyName,
        ]);
    }

    public function generateEmployeeWelcomePdf(int $employeId)
    {
        $employe = \App\Models\Employe::with(['succursale', 'user'])->findOrFail($employeId);
        $user = $employe->user;

        $agencyName = Setting::get('agency_name', tenant('name') ?? 'Insurio Agency');
        $agencyAddress = Setting::get('agency_address', 'Casablanca, Maroc');
        $agencyPhone = Setting::get('agency_phone', '+212 5 22 00 00 00');
        $agencyEmail = Setting::get('agency_email', 'contact@insurio.com');
        $agencyWebsite = Setting::get('agency_website', 'https://' . (tenant('domain') ?? 'www.insurio.ma'));

        $token = optional($user)->activation_token ?: optional($user)->invitation_token;
        if (!$token) {
            $token = \Illuminate\Support\Str::random(64);
            if ($user) {
                $user->update([
                    'activation_token' => $token,
                    'activation_token_expires_at' => now()->addHours(24),
                    'invitation_token' => $token,
                    'invitation_expires_at' => now()->addHours(24),
                ]);
            }
        }

        $activationUrl = route('activation.token', ['token' => $token]);
        $qrCodeSvg = \SimpleSoftwareIO\QrCode\Facades\QrCode::size(160)->margin(1)->generate($activationUrl);
        $qrCodeBase64 = base64_encode($qrCodeSvg);

        $tempPassword = session('created_temp_password_' . $employe->id) ?: ('Ins#' . substr(md5($token), 0, 4) . 'P!');

        $data = [
            'employe' => $employe,
            'user' => $user,
            'agencyName' => $agencyName,
            'agencyAddress' => $agencyAddress,
            'agencyPhone' => $agencyPhone,
            'agencyEmail' => $agencyEmail,
            'agencyWebsite' => $agencyWebsite,
            'tempPassword' => $tempPassword,
            'activationUrl' => $activationUrl,
            'qrCodeBase64' => $qrCodeBase64,
            'expirationDate' => optional($user)->activation_token_expires_at ?: now()->addHours(24),
        ];

        $pdf = Pdf::loadView('pdf.employee-welcome', $data);
        return $pdf->download('lettre_activation_' . $employe->matricule_employe . '.pdf');
    }

    public function printEmployeeWelcomeCard(int $employeId)
    {
        $employe = \App\Models\Employe::with(['succursale', 'user'])->findOrFail($employeId);
        $user = $employe->user;

        $agencyName = Setting::get('agency_name', tenant('name') ?? 'Insurio Agency');
        $agencyAddress = Setting::get('agency_address', 'Casablanca, Maroc');
        $agencyPhone = Setting::get('agency_phone', '+212 5 22 00 00 00');
        $agencyEmail = Setting::get('agency_email', 'contact@insurio.com');
        $agencyWebsite = Setting::get('agency_website', 'https://' . (tenant('domain') ?? 'www.insurio.ma'));

        $token = optional($user)->activation_token ?: optional($user)->invitation_token;
        if (!$token) {
            $token = \Illuminate\Support\Str::random(64);
            if ($user) {
                $user->update([
                    'activation_token' => $token,
                    'activation_token_expires_at' => now()->addHours(24),
                    'invitation_token' => $token,
                    'invitation_expires_at' => now()->addHours(24),
                ]);
            }
        }

        $activationUrl = route('activation.token', ['token' => $token]);
        $qrCodeSvg = \SimpleSoftwareIO\QrCode\Facades\QrCode::size(160)->margin(1)->generate($activationUrl);
        $qrCodeBase64 = base64_encode($qrCodeSvg);

        $tempPassword = session('created_temp_password_' . $employe->id) ?: ('Ins#' . substr(md5($token), 0, 4) . 'P!');

        return view('pdf.employee-welcome', [
            'employe' => $employe,
            'user' => $user,
            'agencyName' => $agencyName,
            'agencyAddress' => $agencyAddress,
            'agencyPhone' => $agencyPhone,
            'agencyEmail' => $agencyEmail,
            'agencyWebsite' => $agencyWebsite,
            'tempPassword' => $tempPassword,
            'activationUrl' => $activationUrl,
            'qrCodeBase64' => $qrCodeBase64,
            'expirationDate' => optional($user)->activation_token_expires_at ?: now()->addHours(24),
        ]);
    }

    private function getTitle(string $type): string
    {
        return match ($type) {
            'carte-verte' => 'Carte Verte d\'Assurance',
            'attestation' => 'Attestation d\'Assurance Automobile',
            'police' => 'Police d\'Assurance Complète',
            'quittance' => 'Quittance de Paiement',
            'recu' => 'Reçu Encaissement',
            'rappel' => 'Avis d' . "'" . 'Echéance & Rappel Client',
            default => 'Document d\'Assurance',
        };
    }
}
