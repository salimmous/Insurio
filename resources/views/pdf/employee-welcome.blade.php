<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Lettre d'Activation & Onboarding - {{ $employe->nom_complet }}</title>
    <style>
        @page { size: A4 portrait; margin: 12mm; }
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; color: #0f172a; margin: 0; padding: 0; font-size: 11px; background-color: #ffffff; }
        
        .header-bar { background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%); color: #ffffff; padding: 24px 30px; border-radius: 12px; }
        .header-table { width: 100%; border-collapse: collapse; }
        .agency-logo { height: 42px; max-width: 180px; object-fit: contain; }
        .agency-name { font-size: 22px; font-weight: 900; letter-spacing: -0.5px; color: #ffffff; margin: 0; }
        .doc-title { font-size: 10px; font-family: monospace; color: #38bdf8; font-weight: bold; letter-spacing: 2px; text-transform: uppercase; margin-top: 4px; }
        
        .agency-contact { text-align: right; font-size: 10px; color: #94a3b8; line-height: 1.5; }
        .agency-contact strong { color: #ffffff; }
        
        .container { padding: 20px 0; }

        .card { background-color: #ffffff; border: 1px solid #e2e8f0; border-radius: 12px; padding: 18px; margin-bottom: 16px; box-shadow: 0 1px 3px rgba(0,0,0,0.03); }
        .card-title { font-size: 11px; font-family: monospace; font-weight: bold; color: #0f172a; text-transform: uppercase; letter-spacing: 1px; margin: 0 0 12px 0; border-bottom: 2px solid #0284c7; padding-bottom: 4px; display: inline-block; }

        .grid-2 { width: 100%; border-collapse: collapse; }
        .grid-2 td { width: 50%; vertical-align: top; padding: 4px 8px; }
        .label { font-size: 9px; font-family: monospace; font-weight: bold; color: #64748b; text-transform: uppercase; }
        .value { font-size: 12px; font-weight: 700; color: #0f172a; margin-top: 2px; }

        .credentials-box { background-color: #f8fafc; border: 2px solid #38bdf8; border-radius: 12px; padding: 18px; margin-top: 10px; }
        .cred-table { width: 100%; border-collapse: collapse; }
        .cred-table td { padding: 8px 12px; vertical-align: top; }
        .cred-label { font-size: 9px; font-family: monospace; font-weight: bold; color: #0369a1; text-transform: uppercase; }
        .cred-value { font-size: 14px; font-family: monospace; font-weight: 900; color: #0f172a; margin-top: 2px; }
        .cred-link { font-size: 11px; font-family: monospace; font-weight: 800; color: #0284c7; word-break: break-all; margin-top: 2px; }
        .cred-warning { font-size: 9.5px; color: #0369a1; margin-top: 10px; font-style: italic; background-color: #e0f2fe; padding: 8px 12px; border-radius: 6px; }

        .qr-section { width: 100%; border-collapse: collapse; }
        .qr-code-cell { width: 170px; text-align: center; vertical-align: middle; }
        .qr-code-img { width: 140px; height: 140px; border: 3px solid #0f172a; border-radius: 12px; padding: 4px; background: #ffffff; }
        .qr-info-cell { vertical-align: middle; padding-left: 20px; }

        .steps-table { width: 100%; border-collapse: separate; border-spacing: 6px 0; margin-top: 10px; }
        .step-card { background-color: #ffffff; border: 1px solid #e2e8f0; border-radius: 10px; padding: 10px; text-align: center; vertical-align: top; }
        .step-num { width: 20px; height: 20px; border-radius: 50%; background-color: #0284c7; color: #ffffff; font-weight: 900; font-size: 10px; line-height: 20px; margin: 0 auto 4px auto; }
        .step-title { font-size: 9.5px; font-weight: 800; color: #0f172a; margin-bottom: 3px; }
        .step-desc { font-size: 8.5px; color: #64748b; line-height: 1.3; }

        .footer { background-color: #0f172a; color: #94a3b8; padding: 12px 30px; font-size: 8.5px; font-family: monospace; text-align: center; border-radius: 8px; margin-top: 20px; }
        .footer strong { color: #ffffff; }

        @media print {
            body { background: #ffffff !important; }
            .header-bar { border-radius: 0; }
            .card { box-shadow: none; border-color: #cbd5e1; }
        }
    </style>
</head>
<body>

    <!-- AGENCY HEADER BANNER -->
    <div class="header-bar">
        <table class="header-table">
            <tr>
                <td style="vertical-align: middle;">
                    @if(tenant('logo_path'))
                        <img src="{{ asset('storage/' . tenant('logo_path')) }}" class="agency-logo">
                    @else
                        <h1 class="agency-name">{{ $agencyName }}</h1>
                    @endif
                    <div class="doc-title">LETTRE D'ACTIVATION EMPLOYÉ & ACCRÉDITATION ENTERPRISE</div>
                </td>
                <td class="agency-contact">
                    <strong>{{ $agencyName }}</strong><br>
                    {{ $agencyAddress }}<br>
                    Tél : {{ $agencyPhone }} • Email : {{ $agencyEmail }}<br>
                    Site Web : {{ $agencyWebsite }}
                </td>
            </tr>
        </table>
    </div>

    <div class="container">

        <!-- 1. EMPLOYEE & ACCREDITATION DETAILS -->
        <div class="card">
            <div class="card-title">1. Employé Accrédité & Informations Générales</div>
            <table class="grid-2">
                <tr>
                    <td>
                        <div class="label">Nom & Prénom</div>
                        <div class="value">{{ $employe->nom_complet }}</div>
                    </td>
                    <td>
                        <div class="label">Matricule Employé</div>
                        <div class="value" style="font-family: monospace; color: #0284c7;">{{ $employe->matricule_employe }}</div>
                    </td>
                </tr>
                <tr>
                    <td style="padding-top: 8px;">
                        <div class="label">Poste / Rôle Officiel</div>
                        <div class="value">{{ $employe->poste }}</div>
                    </td>
                    <td style="padding-top: 8px;">
                        <div class="label">Succursale de Rattachement</div>
                        <div class="value">{{ optional($employe->succursale)->nom ?? 'Siège Agence' }}</div>
                    </td>
                </tr>
                <tr>
                    <td style="padding-top: 8px;">
                        <div class="label">Email Professionnel</div>
                        <div class="value" style="font-family: monospace;">{{ $employe->email }}</div>
                    </td>
                    <td style="padding-top: 8px;">
                        <div class="label">Téléphone GSM & CIN</div>
                        <div class="value">{{ $employe->telephone ?: 'N/A' }} • CIN: {{ $employe->cin ?: 'N/A' }}</div>
                    </td>
                </tr>
            </table>
        </div>

        <!-- 2. ACTIVATION PACKAGE & CREDENTIALS -->
        <div class="card" style="border-color: #38bdf8; background-color: #fafafa;">
            <div class="card-title" style="border-color: #0284c7; color: #0369a1;">2. Pack d'Activation & Identifiants Temporaires</div>
            
            <table class="qr-section" style="margin-bottom: 10px;">
                <tr>
                    <td class="qr-code-cell">
                        @if($qrCodeBase64)
                            <img src="data:image/svg+xml;base64,{{ $qrCodeBase64 }}" class="qr-code-img">
                            <div style="font-size: 8px; font-family: monospace; font-weight: bold; color: #0369a1; margin-top: 4px;">SCANNER POUR ACTIVER</div>
                        @else
                            <div style="font-size: 9px; color: #94a3b8; font-family: monospace;">[QR CODE LINK]</div>
                        @endif
                    </td>
                    <td class="qr-info-cell">
                        <div class="credentials-box">
                            <table class="cred-table">
                                <tr>
                                    <td>
                                        <div class="cred-label">Email Identifiant</div>
                                        <div class="cred-value">{{ $employe->email }}</div>
                                    </td>
                                    <td>
                                        <div class="cred-label">Mot de Passe Temporaire</div>
                                        <div class="cred-value" style="color: #0284c7;">{{ $tempPassword }}</div>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2" style="border-top: 1px solid #e0f2fe; padding-top: 8px;">
                                        <div class="cred-label">Lien d'Activation Sécurisé (Validité 24 Hours)</div>
                                        <div class="cred-link">{{ $activationUrl }}</div>
                                    </td>
                                </tr>
                            </table>
                            
                            <div class="cred-warning">
                                ⏳ <strong>EXPIRATION :</strong> Ce pack d'activation et le mot de passe temporaire expirent le <strong>{{ $expirationDate ? $expirationDate->format('d/m/Y à H:i') : now()->addHours(24)->format('d/m/Y à H:i') }}</strong>.
                            </div>
                        </div>
                    </td>
                </tr>
            </table>
        </div>

        <!-- 3. STEP-BY-STEP INSTRUCTIONS -->
        <div class="card">
            <div class="card-title" style="border-color: #6366f1;">3. Procédure d'Activation en 5 Étapes</div>

            <table class="steps-table">
                <tr>
                    <td class="step-card" style="width: 20%;">
                        <div class="step-num">1</div>
                        <div class="step-title">Accès / QR Code</div>
                        <div class="step-desc">Ouvrez le lien d'activation ou scannez le QR code ci-dessus avec votre smartphone.</div>
                    </td>
                    <td class="step-card" style="width: 20%;">
                        <div class="step-num">2</div>
                        <div class="step-title">Identifiants</div>
                        <div class="step-desc">Saisissez votre Email Pro & le Mot de Passe temporaire d'activation.</div>
                    </td>
                    <td class="step-card" style="width: 20%;">
                        <div class="step-num">3</div>
                        <div class="step-title">Changer Password</div>
                        <div class="step-desc">Définissez votre nouveau mot de passe personnel fort (12+ caractères, majuscule, chiffre, symbole).</div>
                    </td>
                    <td class="step-card" style="width: 20%;">
                        <div class="step-num">4</div>
                        <div class="step-title">Configuration 2FA</div>
                        <div class="step-desc">Scannez la clé 2FA dans votre application (Google Authenticator / 1Password) et validez le code à 6 chiffres.</div>
                    </td>
                    <td class="step-card" style="width: 20%; background-color: #f0fdf4; border-color: #bbf7d0;">
                        <div class="step-num" style="background-color: #16a34a;">5</div>
                        <div class="step-title" style="color: #15803d;">Codes Secours</div>
                        <div class="step-desc" style="color: #166534;">Sauvegardez vos 10 codes de récupération et accédez au Tableau de Bord Insurio.</div>
                    </td>
                </tr>
            </table>
        </div>

    </div>

    <!-- FOOTER BANNER -->
    <div class="footer">
        Powered by <strong>INSURIO Enterprise SaaS Platform</strong> • Document Confidentiel • Généré automatiquement le {{ date('d/m/Y à H:i') }} • Ref: {{ $employe->matricule_employe }}
    </div>

</body>
</html>
