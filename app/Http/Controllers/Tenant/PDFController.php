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
        $agencyName = Setting::get('agency_name', tenant('name') ?? 'Insurio Agency');
        $agencyAddress = Setting::get('agency_address', 'Casablanca, Maroc');
        $agencyPhone = Setting::get('agency_phone', '+212 5 22 00 00 00');
        $agencyEmail = Setting::get('agency_email', 'contact@insurio.com');
        $agencyWebsite = Setting::get('agency_website', 'https://' . (tenant('domain') ?? 'www.insurio.ma'));

        $google2fa = new \PragmaRX\Google2FA\Google2FA();
        $twoFactorSecret = optional($employe->user)->two_factor_secret ?: $google2fa->generateSecretKey();
        
        $qrCodeUrl = $google2fa->getQRCodeUrl($agencyName, $employe->email, $twoFactorSecret);
        $qrCodeSvg = \SimpleSoftwareIO\QrCode\Facades\QrCode::size(180)->margin(1)->generate($qrCodeUrl);
        $qrCodeBase64 = base64_encode($qrCodeSvg);

        $tempPassword = $employe->invitation_token ? substr(md5($employe->invitation_token), 0, 4) . '#92Lm' : 'A7xP#92Lm';

        $data = [
            'employe' => $employe,
            'agencyName' => $agencyName,
            'agencyAddress' => $agencyAddress,
            'agencyPhone' => $agencyPhone,
            'agencyEmail' => $agencyEmail,
            'agencyWebsite' => $agencyWebsite,
            'tempPassword' => $tempPassword,
            'twoFactorSecret' => $twoFactorSecret,
            'qrCodeBase64' => $qrCodeBase64,
        ];

        $pdf = Pdf::loadView('pdf.employee-welcome', $data);
        return $pdf->download('kit_onboarding_' . $employe->matricule_employe . '.pdf');
    }

    public function printEmployeeWelcomeCard(int $employeId)
    {
        $employe = \App\Models\Employe::with(['succursale', 'user'])->findOrFail($employeId);
        $agencyName = Setting::get('agency_name', tenant('name') ?? 'Insurio Agency');
        $agencyAddress = Setting::get('agency_address', 'Casablanca, Maroc');
        $agencyPhone = Setting::get('agency_phone', '+212 5 22 00 00 00');
        $agencyEmail = Setting::get('agency_email', 'contact@insurio.com');
        $agencyWebsite = Setting::get('agency_website', 'https://' . (tenant('domain') ?? 'www.insurio.ma'));

        $google2fa = new \PragmaRX\Google2FA\Google2FA();
        $twoFactorSecret = optional($employe->user)->two_factor_secret ?: $google2fa->generateSecretKey();
        
        $qrCodeUrl = $google2fa->getQRCodeUrl($agencyName, $employe->email, $twoFactorSecret);
        $qrCodeSvg = \SimpleSoftwareIO\QrCode\Facades\QrCode::size(180)->margin(1)->generate($qrCodeUrl);
        $qrCodeBase64 = base64_encode($qrCodeSvg);

        $tempPassword = $employe->invitation_token ? substr(md5($employe->invitation_token), 0, 4) . '#92Lm' : 'A7xP#92Lm';

        return view('pdf.employee-welcome', [
            'employe' => $employe,
            'agencyName' => $agencyName,
            'agencyAddress' => $agencyAddress,
            'agencyPhone' => $agencyPhone,
            'agencyEmail' => $agencyEmail,
            'agencyWebsite' => $agencyWebsite,
            'tempPassword' => $tempPassword,
            'twoFactorSecret' => $twoFactorSecret,
            'qrCodeBase64' => $qrCodeBase64,
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
