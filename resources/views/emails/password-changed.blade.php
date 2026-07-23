<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modification de Mot de Passe - {{ $agencyName }}</title>
    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; background-color: #0f172a; color: #f8fafc; margin: 0; padding: 40px 20px; }
        .container { max-width: 600px; margin: 0 auto; background-color: #1e293b; border-radius: 24px; padding: 40px; border: 1px solid #334155; shadow: 0 20px 25px -5px rgba(0,0,0,0.5); }
        .header { text-align: center; border-bottom: 1px solid #334155; padding-bottom: 24px; margin-bottom: 32px; }
        .logo { max-height: 50px; margin-bottom: 12px; }
        .agency-title { font-size: 24px; font-weight: 900; color: #ffffff; margin: 0; }
        .powered-by { font-size: 11px; font-family: monospace; color: #818cf8; text-transform: uppercase; letter-spacing: 2px; margin-top: 4px; }
        .info-box { background-color: #0f172a; border-radius: 16px; padding: 20px; border: 1px solid #334155; margin: 20px 0; font-size: 13px; color: #94a3b8; }
        .footer { text-align: center; border-top: 1px solid #334155; padding-top: 24px; margin-top: 32px; font-size: 11px; color: #64748b; font-family: monospace; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            @if($agencyLogo)
                <img src="{{ $agencyLogo }}" alt="{{ $agencyName }}" class="logo">
            @endif
            <h1 class="agency-title">{{ $agencyName }}</h1>
            <div class="powered-by">Propulsé par Insurio SaaS</div>
        </div>

        <h2 style="font-size: 20px; font-weight: 800; color: #ffffff; margin-top: 0;">Alerte de Sécurité - Mot de Passe Modifié</h2>

        <p style="font-size: 14px; line-height: 1.6; color: #cbd5e1;">
            Bonjour <strong>{{ $user->name }}</strong>,
        </p>

        <p style="font-size: 14px; line-height: 1.6; color: #cbd5e1;">
            Le mot de passe de votre compte employé a été modifié avec succès le {{ date('d/m/Y à H:i') }}.
        </p>

        <div class="info-box">
            <strong style="color: #fbbf24; display: block; margin-bottom: 6px;">ℹ️ Détails de la Modification :</strong>
            • Adresse IP : <strong>{{ $ipAddress }}</strong><br>
            • Horodatage : <strong>{{ date('d/m/Y H:i:s') }}</strong><br>
            • Méthode : <strong>Procédure d'activation / réinitialisation sécurisée</strong>
        </div>

        <p style="font-size: 13px; line-height: 1.6; color: #f87171;">
            ⚠️ Si vous n'êtes pas à l'origine de cette modification, contactez immédiatement l'administrateur de votre agence.
        </p>

        <div class="footer">
            © {{ date('Y') }} {{ $agencyName }}. Tous droits réservés.<br>
            Plateforme Sécurisée Multi-Tenant • Propulsée par Insurio Enterprise
        </div>
    </div>
</body>
</html>
