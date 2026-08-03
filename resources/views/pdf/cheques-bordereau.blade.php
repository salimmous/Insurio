<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Bordereau Officiel de Remise de Chèques</title>
    <style>
        @page {
            margin: 18px 22px;
        }
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 9.5px;
            color: #0f172a;
            line-height: 1.35;
            margin: 0;
            padding: 0;
        }

        /* Top Brand Header Banner */
        .brand-header {
            width: 100%;
            background-color: #0f172a;
            color: #ffffff;
            padding: 14px 18px;
            border-bottom: 3px solid #3b82f6;
            margin-bottom: 15px;
        }
        .brand-logo {
            font-size: 20px;
            font-weight: 900;
            letter-spacing: 1px;
            color: #ffffff;
            text-transform: uppercase;
        }
        .brand-sub {
            font-size: 9px;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-top: 2px;
        }
        .doc-meta {
            text-align: right;
            color: #e2e8f0;
            font-size: 9px;
        }
        .doc-ref {
            font-family: monospace;
            font-weight: bold;
            font-size: 11px;
            color: #60a5fa;
        }

        /* Document Title */
        .doc-header-block {
            text-align: center;
            margin-bottom: 14px;
        }
        .doc-title {
            font-size: 16px;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            color: #0f172a;
            margin-bottom: 2px;
        }
        .doc-subtitle {
            font-size: 9.5px;
            color: #64748b;
            font-weight: 500;
        }

        /* Executive KPI Summary Cards */
        .kpi-table {
            width: 100%;
            margin-bottom: 16px;
            border-collapse: separate;
            border-spacing: 8px 0;
        }
        .kpi-card {
            background-color: #f8fafc;
            border: 1px solid #cbd5e1;
            border-radius: 6px;
            padding: 8px 12px;
            text-align: center;
        }
        .kpi-card-total {
            background-color: #eff6ff;
            border: 1.5px solid #2563eb;
            border-radius: 6px;
            padding: 8px 12px;
            text-align: center;
        }
        .kpi-label {
            font-size: 8px;
            font-weight: 800;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .kpi-value {
            font-size: 14px;
            font-weight: 900;
            color: #0f172a;
            margin-top: 2px;
        }
        .kpi-value-blue {
            font-size: 15px;
            font-weight: 900;
            color: #1d4ed8;
            font-family: monospace;
            margin-top: 2px;
        }

        /* Corporate Data Table */
        .table-container {
            margin-bottom: 16px;
        }
        .items-table {
            width: 100%;
            border-collapse: collapse;
        }
        .items-table th {
            background-color: #0f172a;
            color: #ffffff;
            font-size: 8.5px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 8px 9px;
            text-align: left;
            border: 1px solid #0f172a;
        }
        .items-table td {
            padding: 7px 9px;
            border-bottom: 1px solid #e2e8f0;
            border-left: 1px solid #f1f5f9;
            border-right: 1px solid #f1f5f9;
            font-size: 9px;
            vertical-align: middle;
        }
        .items-table tr:nth-child(even) {
            background-color: #f8fafc;
        }

        /* Client & Cheque info */
        .client-name {
            font-weight: 800;
            color: #0f172a;
            font-size: 9.5px;
        }
        .client-details {
            font-size: 8px;
            color: #64748b;
            margin-top: 1px;
        }
        .cheque-num {
            font-family: monospace;
            font-weight: 900;
            color: #1d4ed8;
            font-size: 10px;
        }
        .bank-tag {
            font-size: 8px;
            font-weight: 700;
            color: #475569;
        }

        /* Badges */
        .badge {
            display: inline-block;
            padding: 2.5px 7px;
            border-radius: 4px;
            font-size: 8px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }
        .badge-pending { background-color: #fef3c7; color: #92400e; border: 1px solid #fde68a; }
        .badge-deposited { background-color: #dbeafe; color: #1e40af; border: 1px solid #bfdbfe; }
        .badge-collected { background-color: #d1fae5; color: #065f46; border: 1px solid #a7f3d0; }
        .badge-returned { background-color: #ffe4e6; color: #9f1239; border: 1px solid #fecdd3; }

        /* Signatures Section */
        .signature-table {
            width: 100%;
            margin-top: 20px;
        }
        .signature-cell {
            width: 50%;
            vertical-align: top;
        }
        .signature-box {
            border: 1.5px dashed #cbd5e1;
            background-color: #fafafa;
            height: 85px;
            border-radius: 6px;
            padding: 8px 12px;
            position: relative;
        }
        .sig-title {
            font-size: 9px;
            font-weight: 800;
            text-transform: uppercase;
            color: #334155;
            letter-spacing: 0.5px;
            margin-bottom: 4px;
        }
        .sig-watermark {
            font-size: 8.5px;
            color: #cbd5e1;
            text-align: center;
            margin-top: 25px;
            font-style: italic;
        }

        /* Footer Legal Bar */
        .footer-bar {
            margin-top: 20px;
            border-top: 1px solid #e2e8f0;
            padding-top: 8px;
            font-size: 8px;
            color: #94a3b8;
            text-align: center;
        }
    </style>
</head>
<body>

    <!-- Corporate Header Banner -->
    <table class="brand-header">
        <tr>
            <td style="width: 60%;">
                <div class="brand-logo">{{ $agencyName }}</div>
                <div class="brand-sub">Cabinet d'Assurance &amp; Courtage Général</div>
            </td>
            <td class="doc-meta" style="width: 40%;">
                <div>BORDEREAU DE REMISE OFFICIEL</div>
                <div class="doc-ref">REF: BRC-{{ date('Ymd') }}-{{ rand(1000, 9999) }}</div>
                <div style="margin-top: 2px;">Édité le: <strong>{{ $generatedAt }}</strong></div>
            </td>
        </tr>
    </table>

    <!-- Document Title Block -->
    <div class="doc-header-block">
        <div class="doc-title">Bordereau de Remise &amp; Suivi de Chèques</div>
        <div class="doc-subtitle">Document comptable de contrôle et de dépôt bancaire des règlements clients</div>
    </div>

    <!-- Executive KPI Summary Cards -->
    <table class="kpi-table">
        <tr>
            <td class="kpi-card" style="width: 30%;">
                <div class="kpi-label">Nombre de Chèques</div>
                <div class="kpi-value">{{ $totalCount }} chèque(s)</div>
            </td>
            <td class="kpi-card" style="width: 35%;">
                <div class="kpi-label">Date d'Émission du Bordereau</div>
                <div class="kpi-value" style="font-size: 12px; font-family: monospace;">{{ date('d/m/Y') }} à {{ date('H:i') }}</div>
            </td>
            <td class="kpi-card-total" style="width: 35%;">
                <div class="kpi-label" style="color: #1e40af;">Montant Total Remis</div>
                <div class="kpi-value-blue">{{ number_format($totalAmount, 2) }} DH</div>
            </td>
        </tr>
    </table>

    <!-- Data Table -->
    <div class="table-container">
        <table class="items-table">
            <thead>
                <tr>
                    <th style="width: 4%; text-align: center;">#</th>
                    <th style="width: 17%;">N° Chèque &amp; Banque</th>
                    <th style="width: 28%;">Client / Émetteur</th>
                    <th style="width: 15%;">Réf. Contrat</th>
                    <th style="width: 12%;">Échéance</th>
                    <th style="width: 12%;">Dépôt / Versement</th>
                    <th style="width: 12%; text-align: center;">Statut</th>
                    <th style="width: 12%; text-align: right;">Montant (DH)</th>
                </tr>
            </thead>
            <tbody>
                @foreach($cheques as $index => $chq)
                    <tr>
                        <td style="text-align: center; font-weight: bold; color: #64748b;">{{ $index + 1 }}</td>
                        <td>
                            <div class="cheque-num">N° {{ $chq->cheque_number }}</div>
                            <div class="bank-tag">🏦 {{ $chq->bank_name ?? '—' }} {{ $chq->agency ? '('.$chq->agency.')' : '' }}</div>
                        </td>
                        <td>
                            <div class="client-name">{{ $chq->client?->nom_complet ?? $chq->issuer ?? '—' }}</div>
                            <div class="client-details">
                                @if($chq->client?->cin) <strong>CIN:</strong> {{ $chq->client->cin }} @endif
                                @if($chq->client?->telephone) &nbsp;·&nbsp; <strong>Tél:</strong> {{ $chq->client->telephone }} @endif
                            </div>
                        </td>
                        <td style="font-family: monospace;">
                            <div style="font-weight: 700; color: #334155;">{{ $chq->contract?->numero_contrat ?? '—' }}</div>
                            @if($chq->contract?->compagnie?->nom)
                                <div style="font-size: 8px; color: #64748b;">{{ $chq->contract->compagnie->nom }}</div>
                            @endif
                        </td>
                        <td style="font-family: monospace; font-weight: 600;">
                            {{ $chq->due_date ? $chq->due_date->format('d/m/Y') : '—' }}
                        </td>
                        <td style="font-family: monospace;">
                            @if($chq->deposit_date)
                                <div style="color: #2563eb; font-weight: 700;">✓ {{ $chq->deposit_date->format('d/m/Y') }}</div>
                            @else
                                <div style="color: #94a3b8; font-style: italic;">Non déposé</div>
                            @endif
                            @if($chq->collection_date)
                                <div style="font-size: 7.5px; color: #16a34a; font-weight: 700;">Encaissé: {{ $chq->collection_date->format('d/m/Y') }}</div>
                            @endif
                        </td>
                        <td style="text-align: center;">
                            @if(in_array($chq->status, ['received', 'pending', 'created']))
                                <span class="badge badge-pending">En Attente</span>
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
                        <td style="text-align: right; font-family: monospace; font-weight: 900; font-size: 10.5px; color: #0f172a;">
                            {{ number_format($chq->amount, 2) }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Signatures & Validation Block -->
    <table class="signature-table">
        <tr>
            <td class="signature-cell" style="padding-right: 10px;">
                <div class="sig-title">Visa &amp; Validation Agence</div>
                <div class="signature-box">
                    <div style="font-size: 8px; color: #64748b;">Responsable Émission: {{ auth()->user()->name ?? 'Administrateur' }}</div>
                    <div class="sig-watermark">Tampon &amp; Signature de l'Agence</div>
                </div>
            </td>
            <td class="signature-cell" style="padding-left: 10px;">
                <div class="sig-title">Accusé de Réception Banque / Décharge</div>
                <div class="signature-box">
                    <div style="font-size: 8px; color: #64748b;">Nom du Réceptionnaire: .......................................</div>
                    <div class="sig-watermark">Cachet Banque &amp; Date de Dépôt</div>
                </div>
            </td>
        </tr>
    </table>

    <!-- Footer Legal & Traceability Bar -->
    <div class="footer-bar">
        {{ $agencyName }} &nbsp;·&nbsp; {{ $agencyAddress }} &nbsp;·&nbsp; Tél: {{ $agencyPhone }} &nbsp;·&nbsp; Email: {{ $agencyEmail }}
        <br>
        Document généré automatiquement via Insurio Enterprise Core &nbsp;·&nbsp; Certificat de Traitement #{{ md5($generatedAt . $totalAmount) }}
    </div>

</body>
</html>
