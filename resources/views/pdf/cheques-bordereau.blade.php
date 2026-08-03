<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Bordereau de Remise de Chèques</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 10px;
            color: #0f172a;
            line-height: 1.35;
            margin: 15px;
        }
        .header-table {
            width: 100%;
            border-bottom: 2px solid #4f46e5;
            padding-bottom: 10px;
            margin-bottom: 15px;
        }
        .header-logo {
            font-size: 18px;
            font-weight: 800;
            color: #4f46e5;
            text-transform: uppercase;
        }
        .header-info {
            text-align: right;
            font-size: 9px;
            color: #64748b;
        }
        .doc-title {
            text-align: center;
            font-size: 15px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #0f172a;
            margin-bottom: 3px;
        }
        .doc-subtitle {
            text-align: center;
            font-size: 10px;
            color: #64748b;
            margin-bottom: 15px;
        }
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        .items-table th {
            background-color: #f1f5f9;
            color: #334155;
            font-size: 9px;
            font-weight: 800;
            text-transform: uppercase;
            padding: 7px 8px;
            border-bottom: 2px solid #cbd5e1;
            text-align: left;
        }
        .items-table td {
            padding: 7px 8px;
            border-bottom: 1px solid #e2e8f0;
            font-size: 9.5px;
            vertical-align: middle;
        }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .font-bold { font-weight: bold; }
        .font-mono { font-family: monospace; }
        .client-sub {
            font-size: 8.5px;
            color: #64748b;
        }
        .date-sub {
            font-size: 8.5px;
            color: #2563eb;
            font-weight: bold;
        }
        .signature-table {
            width: 100%;
            margin-top: 25px;
        }
        .signature-cell {
            width: 50%;
            vertical-align: top;
        }
        .signature-box {
            border: 1px dashed #cbd5e1;
            height: 75px;
            border-radius: 6px;
            padding: 8px;
            font-size: 9px;
            color: #94a3b8;
        }
        .badge {
            display: inline-block;
            padding: 2px 5px;
            border-radius: 3px;
            font-size: 8.5px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .badge-pending { background: #fef3c7; color: #92400e; }
        .badge-deposited { background: #dbeafe; color: #1e40af; }
        .badge-collected { background: #d1fae5; color: #065f46; }
        .badge-returned { background: #ffe4e6; color: #9f1239; }
    </style>
</head>
<body>

    <table class="header-table">
        <tr>
            <td>
                <div class="header-logo">{{ $agencyName }}</div>
                <div style="font-size: 9px; color: #64748b;">Cabinet d'Assurance &amp; Courtage</div>
            </td>
            <td class="header-info">
                <div>{{ $agencyAddress }}</div>
                <div>Tél: {{ $agencyPhone }} &nbsp;|&nbsp; Email: {{ $agencyEmail }}</div>
                <div style="font-weight: bold; color: #4f46e5; margin-top: 2px;">Imprimé le: {{ $generatedAt }}</div>
            </td>
        </tr>
    </table>

    <div class="doc-title">Bordereau de Remise &amp; Suivi de Chèques</div>
    <div class="doc-subtitle">Liste détaillée des {{ $totalCount }} chèque(s) sélectionné(s) &nbsp;·&nbsp; Montant global: {{ number_format($totalAmount, 2) }} DH</div>

    <table class="items-table">
        <thead>
            <tr>
                <th style="width: 3%;">#</th>
                <th style="width: 15%;">N° Chèque &amp; Banque</th>
                <th style="width: 25%;">Informations Client (Molah)</th>
                <th style="width: 14%;">Contrat</th>
                <th style="width: 13%;">Échéance Prévue</th>
                <th style="width: 13%;">Versement / Dépôt</th>
                <th style="width: 8%; text-align: center;">Statut</th>
                <th style="width: 9%; text-align: right;">Montant (DH)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($cheques as $index => $chq)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>
                        <div class="font-mono font-bold" style="color: #4f46e5; font-size: 10px;">N° {{ $chq->cheque_number }}</div>
                        <div class="client-sub">{{ $chq->bank_name ?? '—' }} {{ $chq->agency ? '('.$chq->agency.')' : '' }}</div>
                    </td>
                    <td>
                        <div class="font-bold" style="color: #0f172a;">{{ $chq->client?->nom_complet ?? $chq->issuer ?? '—' }}</div>
                        <div class="client-sub">
                            @if($chq->client?->cin) CIN: {{ $chq->client->cin }} @endif
                            @if($chq->client?->telephone) &nbsp;|&nbsp; Tél: {{ $chq->client->telephone }} @endif
                        </div>
                    </td>
                    <td class="font-mono">
                        <div>{{ $chq->contract?->numero_contrat ?? '—' }}</div>
                        @if($chq->contract?->compagnie?->nom)
                            <div class="client-sub">{{ $chq->contract->compagnie->nom }}</div>
                        @endif
                    </td>
                    <td class="font-mono">
                        {{ $chq->due_date ? $chq->due_date->format('d/m/Y') : '—' }}
                    </td>
                    <td class="font-mono">
                        @if($chq->deposit_date)
                            <div class="date-sub">✓ {{ $chq->deposit_date->format('d/m/Y') }}</div>
                        @else
                            <div class="client-sub">Non versé</div>
                        @endif
                        @if($chq->collection_date)
                            <div style="font-size: 8px; color: #16a34a; font-weight: bold;">Encaissé: {{ $chq->collection_date->format('d/m/Y') }}</div>
                        @endif
                    </td>
                    <td class="text-center">
                        @if(in_array($chq->status, ['received', 'pending', 'created']))
                            <span class="badge badge-pending">Attente</span>
                        @elseif($chq->status === 'deposited')
                            <span class="badge badge-deposited">Versé</span>
                        @elseif(in_array($chq->status, ['collected', 'validated']))
                            <span class="badge badge-collected">Encaissé</span>
                        @elseif(in_array($chq->status, ['returned', 'rejected']))
                            <span class="badge badge-returned">Impayé</span>
                        @else
                            <span class="badge">{{ $chq->status }}</span>
                        @endif
                    </td>
                    <td class="text-right font-mono font-bold" style="font-size: 10px;">
                        {{ number_format($chq->amount, 2) }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <table style="width: 100%;">
        <tr>
            <td style="width: 50%; vertical-align: top;">
                <div style="font-size: 9px; color: #64748b; margin-top: 5px;">
                    * Ce document officiel atteste la liste des chèques remis ou traités par l'agence à la date et heure indiquées ci-dessus.
                </div>
            </td>
            <td style="width: 50%;">
                <table style="width: 100%; border-collapse: collapse; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 6px;">
                    <tr>
                        <td style="padding: 5px 8px; font-weight: bold; font-size: 10px;">Nombre total de chèques:</td>
                        <td style="padding: 5px 8px; text-align: right; font-weight: bold; font-size: 10px;">{{ $totalCount }}</td>
                    </tr>
                    <tr style="border-top: 2px solid #4f46e5;">
                        <td style="padding: 7px 8px; font-weight: 800; font-size: 11px; color: #4f46e5;">MONTANT TOTAL GLOBAL:</td>
                        <td style="padding: 7px 8px; text-align: right; font-weight: 800; font-size: 12px; color: #4f46e5; font-family: monospace;">
                            {{ number_format($totalAmount, 2) }} DH
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <table class="signature-table">
        <tr>
            <td class="signature-cell" style="padding-right: 15px;">
                <div style="font-weight: bold; margin-bottom: 4px; font-size: 9.5px; text-transform: uppercase; color: #475569;">Visa &amp; Signature Responsable Agence</div>
                <div class="signature-box">Signature &amp; Cachet de l'Agence</div>
            </td>
            <td class="signature-cell" style="padding-left: 15px;">
                <div style="font-weight: bold; margin-bottom: 4px; font-size: 9.5px; text-transform: uppercase; color: #475569;">Accusé de Décharge Banque / Caisse</div>
                <div class="signature-box">Tampon &amp; Signature du Réceptionnaire</div>
            </td>
        </tr>
    </table>

</body>
</html>
