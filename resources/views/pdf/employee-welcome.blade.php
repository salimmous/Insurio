<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Kit d'Onboarding Employé - {{ $employe->nom_complet }}</title>
    <style>
        @page { margin: 0; }
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; color: #1e293b; margin: 0; padding: 0; font-size: 11px; background-color: #f8fafc; }
        
        .header-bar { background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%); color: #ffffff; padding: 30px 40px; }
        .header-table { width: 100%; border-collapse: collapse; }
        .agency-logo { height: 42px; max-width: 180px; object-fit: contain; }
        .agency-name { font-size: 22px; font-weight: 900; letter-spacing: -0.5px; color: #ffffff; margin: 0; }
        .doc-title { font-size: 10px; font-family: monospace; color: #38bdf8; font-weight: bold; letter-spacing: 2px; text-transform: uppercase; margin-top: 4px; }
        
        .agency-contact { text-align: right; font-size: 10px; color: #94a3b8; line-height: 1.5; }
        .agency-contact strong { color: #ffffff; }
        
        .container { padding: 30px 40px; }

        .card { background-color: #ffffff; border: 1px solid #e2e8f0; border-radius: 12px; padding: 20px; margin-bottom: 20px; box-shadow: 0 1px 3px rgba(0,0,0,0.05); }
        .card-title { font-size: 11px; font-family: monospace; font-weight: bold; color: #0f172a; text-transform: uppercase; letter-spacing: 1px; margin: 0 0 14px 0; border-bottom: 2px solid #38bdf8; padding-bottom: 6px; display: inline-block; }

        .grid-2 { width: 100%; border-collapse: collapse; }
        .grid-2 td { width: 50%; vertical-align: top; padding: 4px 8px; }
        .label { font-size: 9px; font-family: monospace; font-weight: bold; color: #64748b; text-transform: uppercase; }
        .value { font-size: 12px; font-weight: 700; color: #0f172a; margin-top: 2px; }

        .temp-credentials-box { background-color: #f0fdf4; border: 1.5px dashed #22c55e; border-radius: 10px; padding: 16px; margin-top: 10px; text-align: center; }
        .cred-label { font-size: 9px; font-family: monospace; font-weight: bold; color: #15803d; text-transform: uppercase; }
        .cred-value { font-size: 16px; font-family: monospace; font-weight: 900; color: #166534; letter-spacing: 1px; margin-top: 4px; }
        .cred-warning { font-size: 9.5px; color: #15803d; margin-top: 8px; font-style: italic; }

        .qr-section { width: 100%; border-collapse: collapse; }
        .qr-code-cell { width: 190px; text-align: center; vertical-align: middle; }
        .qr-code-img { width: 160px; height: 160px; border: 4px solid #ffffff; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
        .qr-info-cell { vertical-align: middle; padding-left: 20px; }

        .secret-key-box { background-color: #f8fafc; border: 1px solid #cbd5e1; border-radius: 8px; padding: 8px 12px; font-family: monospace; font-size: 13px; font-weight: bold; color: #0f172a; display: inline-block; letter-spacing: 2px; margin-top: 6px; }

        .steps-table { width: 100%; border-collapse: separate; border-spacing: 8px 0; margin-top: 10px; }
        .step-card { background-color: #ffffff; border: 1px solid #e2e8f0; border-radius: 10px; padding: 12px; text-align: center; vertical-align: top; }
        .step-num { width: 22px; height: 22px; border-radius: 50%; background-color: #0284c7; color: #ffffff; font-weight: 900; font-size: 11px; line-height: 22px; margin: 0 auto 6px auto; }
        .step-title { font-size: 10px; font-weight: 800; color: #0f172a; margin-bottom: 4px; }
        .step-desc { font-size: 9px; color: #64748b; line-height: 1.3; }

        .footer { background-color: #0f172a; color: #94a3b8; padding: 16px 40px; font-size: 9px; font-family: monospace; text-align: center; position: fixed; bottom: 0; left: 0; right: 0; }
        .footer strong { color: #ffffff; }
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
                    <div class="doc-title">KIT D'ACTIVATION EMPLOYÉ & ACCRÉDITATION SÉCURISÉE</div>
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

        <!-- 1. EMPLOYEE INFORMATION -->
        <div class="card">
            <div class="card-title">1. Informations de l'Employé Accrédité</div>
            <table class="grid-2">
                <tr>
                    <td>
                        <div class="label">Nom & Prénom</div>
                        <div class="value">{{ $employe->nom_complet }}</div>
                    </td>
                    <td>
                        <div class="label">Matricule Identifiant</div>
                        <div class="value" style="font-family: monospace; color: #0284c7;">{{ $employe->matricule_employe }}</div>
                    </td>
                </tr>
                <tr>
                    <td style="padding-top: 10px;">
                        <div class="label">Poste / Fonction</div>
                        <div class="value">{{ $employe->poste }}</div>
                    </td>
                    <td style="padding-top: 10px;">
                        <div class="label">Succursale de Rattachement</div>
                        <div class="value">{{ optional($employe->succursale)->nom ?? 'Siège Agence' }}</div>
                    </td>
                </tr>
                <tr>
                    <td style="padding-top: 10px;">
                        <div class="label">Email Professionnel</div>
                        <div class="value" style="font-family: monospace;">{{ $employe->email }}</div>
                    </td>
                    <td style="padding-top: 10px;">
                        <div class="label">Téléphone GSM & N° CIN</div>
                        <div class="value">{{ $employe->telephone ?: 'N/A' }} • CIN: {{ $employe->cin ?: 'N/A' }}</div>
                    </td>
                </tr>
            </table>
        </div>

        <!-- 2. ACCOUNT ACCESS (TEMPORARY CREDENTIALS) -->
        <div class="card" style="border-color: #bbf7d0; background-color: #f6fef9;">
            <div class="card-title" style="border-color: #22c55e; color: #15803d;">2. Identifiants d'Accès Temporaires</div>
            
            <div class="temp-credentials-box">
                <table style="width: 100%; border-collapse: collapse;">
                    <tr>
                        <td style="width: 50%; text-align: center; border-right: 1px solid #bbf7d0;">
                            <div class="cred-label">Identifiant (Email Pro)</div>
                            <div class="cred-value" style="font-size: 14px;">{{ $employe->email }}</div>
                        </td>
                        <td style="width: 50%; text-align: center;">
                            <div class="cred-label">Mot de Passe Temporaire</div>
                            <div class="cred-value">{{ $tempPassword }}</div>
                        </td>
                    </tr>
                </table>
                <div class="cred-warning">
                    ⚠️ <strong>IMPORTANT :</strong> Ce mot de passe est à usage unique pour votre première connexion. Vous serez immédiatement invité à définir votre mot de passe personnel sécurisé.
                </div>
            </div>
        </div>

        <!-- 3. TWO-FACTOR AUTHENTICATION (2FA) -->
        <div class="card">
            <div class="card-title" style="border-color: #38bdf8;">3. Configuration de la Clé de Sécurité 2FA (Double Authentification)</div>
            
            <table class="qr-section">
                <tr>
                    <td class="qr-code-cell">
                        @if($qrCodeBase64)
                            <img src="data:image/svg+xml;base64,{{ $qrCodeBase64 }}" class="qr-code-img">
                        @else
                            <div style="font-size: 10px; color: #94a3b8; font-family: monospace;">[QR CODE 2FA]</div>
                        @endif
                    </td>
                    <td class="qr-info-cell">
                        <div style="font-size: 11px; font-weight: bold; color: #0f172a;">Instruction 2FA Google Authenticator / Authy :</div>
                        <p style="font-size: 10px; color: #475569; margin: 4px 0 10px 0; line-height: 1.4;">
                            Ouvrez votre application d'authentification sur smartphone (Google Authenticator, Authy ou Microsoft Authenticator) et scannez le QR code ci-contre.
                        </p>

                        <div class="label">Clé Secrète Manuel (Saisie manuelle si QR code illisible) :</div>
                        <div class="secret-key-box">{{ chunk_split($twoFactorSecret ?: 'JBSWY3DPEHPK3PXP', 4, ' ') }}</div>
                    </td>
                </tr>
            </table>
        </div>

        <!-- 4. FIRST LOGIN PROCEDURE (5 STEPS) -->
        <div class="card">
            <div class="card-title" style="border-color: #6366f1;">4. Procédure de Première Connexion (5 Étapes Simplifiées)</div>

            <table class="steps-table">
                <tr>
                    <td class="step-card" style="width: 20%;">
                        <div class="step-num">1</div>
                        <div class="step-title">Connexion</div>
                        <div class="step-desc">Connectez-vous avec votre Email Pro & le Mot de passe temporaire.</div>
                    </td>
                    <td class="step-card" style="width: 20%;">
                        <div class="step-num">2</div>
                        <div class="step-title">Scan 2FA</div>
                        <div class="step-desc">Scannez le QR Code avec Google Authenticator.</div>
                    </td>
                    <td class="step-card" style="width: 20%;">
                        <div class="step-num">3</div>
                        <div class="step-title">Code 6 chiffres</div>
                        <div class="step-desc">Saisissez le code temporaire à 6 chiffres affiché sur l'application.</div>
                    </td>
                    <td class="step-card" style="width: 20%;">
                        <div class="step-num">4</div>
                        <div class="step-title">Nouveau MPT</div>
                        <div class="step-desc">Créez votre nouveau mot de passe personnel sécurisé.</div>
                    </td>
                    <td class="step-card" style="width: 20%; background-color: #f0fdf4; border-color: #bbf7d0;">
                        <div class="step-num" style="background-color: #16a34a;">5</div>
                        <div class="step-title" style="color: #15803d;">Compte Actif</div>
                        <div class="step-desc" style="color: #166534;">Votre accès Enterprise Insurio est validé et sécurisé !</div>
                    </td>
                </tr>
            </table>
        </div>

    </div>

    <!-- FOOTER BANNER -->
    <div class="footer">
        Powered by <strong>INSURIO Enterprise SaaS Platform</strong> • Document Confidentiel • Généré automatiquement le {{ date('d/m/Y à H:i') }} • Version 2.4.0
    </div>

</body>
</html>
