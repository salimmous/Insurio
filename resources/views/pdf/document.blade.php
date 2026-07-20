<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $title }}</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 11px;
            color: #333333;
            line-height: 1.4;
            margin: 0;
            padding: 0;
        }
        .header-table {
            width: 100%;
            border-bottom: 2px solid #4f46e5;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }
        .header-logo {
            font-size: 24px;
            font-weight: bold;
            color: #4f46e5;
        }
        .header-info {
            text-align: right;
            font-size: 10px;
            color: #666666;
        }
        .title {
            text-align: center;
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 20px;
            text-transform: uppercase;
            color: #111111;
            letter-spacing: 1px;
        }
        .section-title {
            font-size: 11px;
            font-weight: bold;
            color: #4f46e5;
            border-bottom: 1px solid #e5e7eb;
            padding-bottom: 3px;
            margin-top: 15px;
            margin-bottom: 8px;
            text-transform: uppercase;
        }
        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        .info-table td {
            padding: 4px 0;
            vertical-align: top;
        }
        .info-table .label {
            font-weight: bold;
            color: #555555;
            width: 30%;
        }
        .info-table .value {
            color: #111111;
            width: 70%;
        }
        .grid-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            margin-bottom: 15px;
        }
        .grid-table th {
            background-color: #f9fafb;
            border: 1px solid #e5e7eb;
            padding: 6px;
            font-weight: bold;
            text-align: left;
            font-size: 9px;
            text-transform: uppercase;
            color: #4b5563;
        }
        .grid-table td {
            border: 1px solid #e5e7eb;
            padding: 6px;
            font-size: 10px;
        }
        .footer {
            position: absolute;
            bottom: 20px;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 9px;
            color: #999999;
            border-top: 1px solid #e5e7eb;
            padding-top: 10px;
        }
        .watermark {
            position: absolute;
            top: 45%;
            left: 15%;
            font-size: 60px;
            color: rgba(79, 70, 229, 0.05);
            transform: rotate(-35deg);
            font-weight: bold;
            text-transform: uppercase;
            z-index: -1000;
        }
    </style>
</head>
<body>

    <div class="watermark">{{ $type }}</div>

    <!-- Dynamic Header -->
    <table class="header-table">
        <tr>
            <td>
                @if($agencyLogo)
                    <img src="{{ $agencyLogo }}" style="max-height: 50px;">
                @else
                    <span class="header-logo">{{ $agencyName }}</span>
                @endif
            </td>
            <td class="header-info">
                <strong>{{ $agencyName }}</strong><br>
                {{ $agencyAddress }}<br>
                Tél: {{ $agencyPhone }} | Email: {{ $agencyEmail }}
            </td>
        </tr>
    </table>

    <!-- Document Title -->
    <div class="title">{{ $title }}</div>

    <!-- Client Info -->
    <div class="section-title">Informations du Client / Assuré</div>
    <table class="info-table">
        <tr>
            <td class="label">Nom complet:</td>
            <td class="value">{{ $contrat->client->nom_complet }}</td>
        </tr>
        <tr>
            <td class="label">CIN:</td>
            <td class="value">{{ $contrat->client->cin ?? 'Non fourni' }}</td>
        </tr>
        <tr>
            <td class="label">Téléphone / Email:</td>
            <td class="value">{{ $contrat->client->telephone ?? 'N/A' }} / {{ $contrat->client->email ?? 'N/A' }}</td>
        </tr>
    </table>

    <!-- Contrat / Garantie Info -->
    <div class="section-title">Détails du Contrat d'Assurance</div>
    <table class="info-table">
        <tr>
            <td class="label">Numéro de Contrat:</td>
            <td class="value" style="font-family: monospace; font-weight: bold;">{{ $contrat->numero_contrat }}</td>
        </tr>
        <tr>
            <td class="label">Numéro de Police:</td>
            <td class="value">{{ $contrat->police ?? 'N/A' }}</td>
        </tr>
        <tr>
            <td class="label">Compagnie d'Assurance:</td>
            <td class="value">{{ $contrat->compagnie->nom }}</td>
        </tr>
        <tr>
            <td class="label">Dates d'Effet / Échéance:</td>
            <td class="value">Du <strong>{{ $contrat->date_effet->format('d/m/Y') }}</strong> au <strong>{{ $contrat->date_echeance->format('d/m/Y') }}</strong> ({{ $contrat->nbr_mois }} mois)</td>
        </tr>
        @if($contrat->succursale)
            <tr>
                <td class="label">Succursale émettrice:</td>
                <td class="value">{{ $contrat->succursale->nom }} ({{ $contrat->succursale->ville }})</td>
            </tr>
        @endif
    </table>

    <!-- Vehicle Info -->
    <div class="section-title">Véhicule Assuré</div>
    <table class="info-table">
        <tr>
            <td class="label">Matricule:</td>
            <td class="value" style="font-family: monospace;">{{ $contrat->matricule ?? $contrat->vehicule?->matricule }}</td>
        </tr>
        <tr>
            <td class="label">Marque / Modèle:</td>
            <td class="value">{{ $contrat->marque ?? $contrat->vehicule?->marque }} {{ $contrat->vehicule?->modele }}</td>
        </tr>
        <tr>
            <td class="label">Caractéristiques:</td>
            <td class="value">{{ $contrat->carburant ?? 'N/A' }} | {{ $contrat->puissance_fiscale ?? 'N/A' }} CV | {{ $contrat->nb_places ?? 'N/A' }} places</td>
        </tr>
    </table>

    <!-- Pricing / Primes Table -->
    <div class="section-title">Tableau des Primes et Garanties</div>
    <table class="grid-table">
        <thead>
            <tr>
                <th>Garantie / Rubrique</th>
                <th style="text-align: right;">Montant (DH)</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Responsabilité Civile (RC)</td>
                <td style="text-align: right; font-family: monospace;">{{ number_format($contrat->prime_rc, 2) }}</td>
            </tr>
            <tr>
                <td>Défense et Recours</td>
                <td style="text-align: right; font-family: monospace;">{{ number_format($contrat->def_rec, 2) }}</td>
            </tr>
            @if((float)$contrat->tierce > 0)
                <tr>
                    <td>Garantie Tierce</td>
                    <td style="text-align: right; font-family: monospace;">{{ number_format($contrat->tierce, 2) }}</td>
                </tr>
            @endif
            @if((float)$contrat->collision > 0)
                <tr>
                    <td>Garantie Collision</td>
                    <td style="text-align: right; font-family: monospace;">{{ number_format($contrat->collision, 2) }}</td>
                </tr>
            @endif
            <tr>
                <td>Taxes (Auto & PTA)</td>
                <td style="text-align: right; font-family: monospace;">{{ number_format($contrat->total_taxe, 2) }}</td>
            </tr>
            <tr>
                <td>Timbre et Accessoires</td>
                <td style="text-align: right; font-family: monospace;">{{ number_format((float)$contrat->timbre + (float)$contrat->accessoires, 2) }}</td>
            </tr>
            <tr style="background-color: #f9fafb; font-weight: bold;">
                <td>PRIME TOTALE (TTC)</td>
                <td style="text-align: right; font-family: monospace; font-size: 11px; color: #4f46e5;">{{ number_format($contrat->prime_totale, 2) }} DH</td>
            </tr>
        </tbody>
    </table>

    <!-- Footer -->
    <div class="footer">
        Document généré automatiquement par la plateforme Insurio le {{ now()->format('d/m/Y H:i') }}<br>
        {{ $agencyName }} — Cabinet agréé par l'ACAPS
    </div>

</body>
</html>
