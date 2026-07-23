<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Bienvenue chez {{ $agencyName }}</title>
    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; background-color: #0f172a; color: #f8fafc; margin: 0; padding: 40px 20px; }
        .container { max-width: 600px; margin: 0 auto; background-color: #1e293b; border-radius: 24px; padding: 40px; border: 1px solid #334155; shadow: 0 20px 25px -5px rgba(0,0,0,0.5); }
        .header { text-align: center; border-bottom: 1px solid #334155; padding-bottom: 24px; margin-bottom: 32px; }
        .logo { max-height: 50px; margin-bottom: 12px; }
        .agency-title { font-size: 24px; font-weight: 900; color: #ffffff; margin: 0; }
        .powered-by { font-size: 11px; font-family: monospace; color: #818cf8; text-transform: uppercase; letter-spacing: 2px; margin-top: 4px; }
        .btn { display: inline-block; background-color: #10b981; color: #ffffff; font-weight: 800; font-size: 14px; padding: 16px 36px; border-radius: 12px; text-decoration: none; margin: 24px 0; text-align: center; shadow: 0 10px 15px -3px rgba(16, 185, 129, 0.4); }
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

        <h2 style="font-size: 20px; font-weight: 800; color: #ffffff; margin-top: 0;">Félicitations {{ $employe->prenom }} {{ $employe->nom }} !</h2>

        <p style="font-size: 14px; line-height: 1.6; color: #cbd5e1;">
            Votre compte employé a été activé avec succès avec la double authentification (2FA) configurée.
        </p>

        <p style="font-size: 14px; line-height: 1.6; color: #cbd5e1;">
            Vous pouvez désormais accéder à votre espace de travail professionnel <strong>{{ $agencyName }}</strong>.
        </p>

        <div style="text-align: center;">
            <a href="{{ route('login') }}" class="btn">Accéder à l'Espace de Travail ➔</a>
        </div>

        <div class="info-box">
            <strong style="color: #10b981; display: block; margin-bottom: 6px;">🛡️ Résumé de votre profil :</strong>
            • Matricule : <strong>{{ $employe->matricule_employe }}</strong><br>
            • Poste : <strong>{{ $employe->poste }}</strong><br>
            • Statut 2FA : <strong>Google Authenticator Enrôlé</strong>
        </div>

        <div class="footer">
            © {{ date('Y') }} {{ $agencyName }}. Tous droits réservés.<br>
            Plateforme Sécurisée Multi-Tenant • Propulsée par Insurio Enterprise
        </div>
    </div>
</body>
</html>
