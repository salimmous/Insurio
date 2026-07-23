<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Invitation Agence - {{ $agencyName }}</title>
    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; background-color: #0f172a; color: #f8fafc; margin: 0; padding: 40px 20px; }
        .container { max-width: 600px; margin: 0 auto; background-color: #1e293b; border-radius: 24px; padding: 40px; border: 1px solid #334155; shadow: 0 20px 25px -5px rgba(0,0,0,0.5); }
        .header { text-align: center; border-bottom: 1px solid #334155; padding-bottom: 24px; margin-bottom: 32px; }
        .logo { max-height: 50px; margin-bottom: 12px; }
        .agency-title { font-size: 24px; font-weight: 900; color: #ffffff; margin: 0; }
        .powered-by { font-size: 11px; font-family: monospace; color: #818cf8; text-transform: uppercase; letter-spacing: 2px; margin-top: 4px; }
        .btn { display: inline-block; background-color: #4f46e5; color: #ffffff; font-weight: 800; font-size: 14px; padding: 16px 36px; border-radius: 12px; text-decoration: none; margin: 24px 0; text-align: center; shadow: 0 10px 15px -3px rgba(79, 70, 229, 0.4); }
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

        <h2 style="font-size: 20px; font-weight: 800; color: #ffffff; margin-top: 0;">Bienvenue {{ $employe->prenom }} {{ $employe->nom }} !</h2>

        <p style="font-size: 14px; line-height: 1.6; color: #cbd5e1;">
            Votre profil d'employé a été créé par l'administrateur de l'agence <strong>{{ $agencyName }}</strong>.
        </p>

        <p style="font-size: 14px; line-height: 1.6; color: #cbd5e1;">
            Pour accéder à votre espace de travail professionnel, veuillez cliquer sur le bouton ci-dessous pour configurer votre mot de passe et votre double authentification (2FA) obligatoire.
        </p>

        <div style="text-align: center;">
            <a href="{{ $activationUrl }}" class="btn">Activer Mon Compte Employé ➔</a>
        </div>

        <div class="info-box">
            <strong style="color: #fbbf24; display: block; margin-bottom: 6px;">⚠️ Information de Sécurité & Expiration:</strong>
            • Ce lien d'activation est à usage unique et expire dans <strong>48 heures</strong> (le {{ $user->invitation_expires_at ? $user->invitation_expires_at->format('d/m/Y à H:i') : '48h' }}).<br>
            • L'administrateur de votre agence ne peut pas définir ou connaître votre mot de passe.<br>
            • Une application d'authentification (Google/Microsoft Authenticator, Authy) sera requise lors de l'activation.
        </div>

        <div class="footer">
            © {{ date('Y') }} {{ $agencyName }}. Tous droits réservés.<br>
            Plateforme Sécurisée Multi-Tenant • Propulsée par Insurio Enterprise
        </div>
    </div>
</body>
</html>
