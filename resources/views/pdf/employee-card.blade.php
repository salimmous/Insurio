<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Fiche Employé - {{ $employe->nom_complet }}</title>
    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; color: #1e293b; margin: 0; padding: 20px; font-size: 12px; }
        .card { border: 2px solid #6366f1; border-radius: 16px; padding: 24px; max-width: 600px; margin: 0 auto; background-color: #ffffff; }
        .header { text-align: center; border-bottom: 2px solid #f1f5f9; padding-bottom: 16px; margin-bottom: 20px; }
        .agency-name { font-size: 20px; font-weight: 900; color: #0f172a; text-transform: uppercase; margin: 0; }
        .badge-title { font-size: 10px; font-family: monospace; color: #6366f1; font-weight: bold; letter-spacing: 2px; text-transform: uppercase; margin-top: 4px; }
        .avatar { width: 64px; height: 64px; border-radius: 50%; background-color: #4f46e5; color: #ffffff; font-size: 24px; font-weight: 900; text-align: center; line-height: 64px; margin: 0 auto 16px auto; }
        .name { font-size: 22px; font-weight: 900; text-align: center; color: #0f172a; margin: 0; }
        .poste { font-size: 13px; text-align: center; color: #6366f1; font-weight: bold; margin-top: 4px; }
        .details-table { width: 100%; border-collapse: collapse; margin-top: 24px; }
        .details-table td { padding: 10px 12px; border-bottom: 1px solid #f1f5f9; }
        .details-table td.label { font-weight: bold; color: #64748b; width: 35%; text-transform: uppercase; font-size: 10px; font-family: monospace; }
        .details-table td.value { font-weight: 700; color: #0f172a; }
        .footer { margin-top: 24px; padding-top: 16px; border-top: 1px solid #f1f5f9; text-align: center; font-size: 9px; color: #94a3b8; font-family: monospace; }
    </style>
</head>
<body>
    <div class="card">
        <div class="header">
            <h1 class="agency-name">{{ $agencyName }}</h1>
            <div class="badge-title">Carte d'Accréditation Employé • Insurio Enterprise</div>
        </div>

        <div class="avatar">
            {{ strtoupper(substr($employe->prenom, 0, 1)) }}{{ strtoupper(substr($employe->nom, 0, 1)) }}
        </div>

        <h2 class="name">{{ $employe->nom_complet }}</h2>
        <div class="poste">{{ $employe->poste }}</div>

        <table class="details-table">
            <tr>
                <td class="label">Matricule Employé</td>
                <td class="value">{{ $employe->matricule_employe }}</td>
            </tr>
            <tr>
                <td class="label">Adresse Email Pro</td>
                <td class="value">{{ $employe->email }}</td>
            </tr>
            <tr>
                <td class="label">Téléphone GSM</td>
                <td class="value">{{ $employe->telephone ?: 'N/A' }}</td>
            </tr>
            <tr>
                <td class="label">N° CIN</td>
                <td class="value">{{ $employe->cin ?: 'N/A' }}</td>
            </tr>
            <tr>
                <td class="label">Succursale</td>
                <td class="value">{{ optional($employe->succursale)->nom ?? 'Siège Agence' }}</td>
            </tr>
            <tr>
                <td class="label">Taux Commission Défaut</td>
                <td class="value">{{ number_format($employe->taux_commission_defaut, 2) }}%</td>
            </tr>
            <tr>
                <td class="label">Statut du Compte</td>
                <td class="value" style="color: #10b981;">
                    {{ strtoupper($employe->statut) }} (2FA HARDENED)
                </td>
            </tr>
        </table>

        <div class="footer">
            Document officiel généré le {{ date('d/m/Y à H:i') }} • Insurio Multi-Tenant SaaS Engine<br>
            {{ $agencyName }} — Authentification d'entreprise sécurisée
        </div>
    </div>
</body>
</html>
