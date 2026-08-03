<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Bordereau de Remise de Chèques</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 11px;
            color: #1e293b;
            line-height: 1.4;
            margin: 20px;
        }
        .header-table {
            width: 100%;
            border-bottom: 2px solid #4f46e5;
            padding-bottom: 12px;
            margin-bottom: 20px;
        }
        .header-logo {
            font-size: 20px;
            font-weight: 800;
            color: #4f46e5;
            text-transform: uppercase;
        }
        .header-info {
            text-align: right;
            font-size: 10px;
            color: #64748b;
        }
        .doc-title {
            text-align: center;
            font-size: 16px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #0f172a;
            margin-bottom: 4px;
        }
        .doc-subtitle {
            text-align: center;
            font-size: 10px;
            color: #64748b;
            margin-bottom: 20px;
        }
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .items-table th {
            background-color: #f8fafc;
            color: #475569;
            font-size: 10px;
            font-weight: 800;
            text-transform: uppercase;
            padding: 8px 10px;
            border-bottom: 2px solid #e2e8f0;
            text-align: left;
        }
        .items-table td {
            padding: 8px 10px;
            border-bottom: 1px solid #f1f5f9;
            font-size: 10px;
            vertical-align: middle;
        }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .font-bold { font-weight: bold; }
        .font-mono { font-family: monospace; }
        .signature-table {
            width: 100%;
            margin-top: 30px;
        }
        .signature-cell {
            width: 50%;
            vertical-align: top;
        }
        .signature-box {
            border: 1px dashed #cbd5e1;
            height: 80px;
            border-radius: 6px;
            padding: 10px;
            font-size: 10px;
            color: #94a3b8;
        }
        .badge {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 9px;
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
                <div style="font-size: 10px; color: #64748b;">Cabinet d'Assurance &amp; Courtage</div>
            </td>
            <td class="header-info">
                <div>{{ $agencyAddress }}</div>
                <div>Tél: {{ $agencyPhone }} &nbsp;|&nbsp; Email: {{ $agencyEmail }}</div>
                <div>Date d'impression: {{ $generatedAt }}</div>
            </td>
        </tr>
    </table>

    <div class="doc-title">Bordereau de Remise &amp; Suivi de Chèques</div>
    <div class="doc-subtitle">Liste détaillée des {{ $totalCount }} chèque(s) sélectionné(s)</div>

    <table class="items-table">
        <thead>
            <tr>
                <th style="width: 4%;">#</th>
                <th style="width: 16%;">N° Chèque</th>
                <th style="width: 18%;">Banque</th>
                <th style="width: 24%;">Client / Émetteur</th>
                <th style="width: 16%;">Contrat</th>
                <th style="width: 12%;">Échéance</th>
                <th style="width: 10%; text-align: center;">Statut</th>
                <th style="width: 15%; text-align: right;">Montant (DH)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($cheques as $index => $chq)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td class="font-mono font-bold" style="color: #4f46e5;">N° {{ $chq->cheque_number }}</td>
                    <td>{{ $chq->bank_name ?? '—' }}</td>
                    <td class="font-bold">{{ $chq->client?->nom_complet ?? $chq->issuer ?? '—' }}</td>
                    <td class="font-mono">{{ $chq->contract?->numero_contrat ?? '—' }}</td>
                    <td class="font-mono">{{ $chq->due_date ? $chq->due_date->format('d/m/Y') : '—' }}</td>
                    <td class="text-center">
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
                    <td class="text-right font-mono font-bold" style="font-size: 11px;">
                        {{ number_format($chq->amount, 2) }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <table style="width: 100%;">
        <tr>
            <td style="width: 50%; vertical-align: top;">
                <div style="font-size: 10px; color: #64748b; margin-top: 5px;">
                    * Ce bordereau récapitule les chèques sélectionnés pour remise bancaire ou suivi comptable.
                </div>
            </td>
            <td style="width: 50%;">
                <table style="width: 100%; border-collapse: collapse; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 6px;">
                    <tr>
                        <td style="padding: 6px; font-weight: bold;">Nombre total de chèques:</td>
                        <td style="padding: 6px; text-align: right; font-weight: bold;">{{ $totalCount }}</td>
                    </tr>
                    <tr style="border-top: 2px solid #4f46e5;">
                        <td style="padding: 8px 6px; font-weight: 800; font-size: 12px; color: #4f46e5;">MONTANT TOTAL:</td>
                        <td style="padding: 8px 6px; text-align: right; font-weight: 800; font-size: 13px; color: #4f46e5; font-family: monospace;">
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
                <div style="font-weight: bold; margin-bottom: 4px; font-size: 10px; text-transform: uppercase; color: #475569;">Visa Responsable Agence</div>
                <div class="signature-box">Signature &amp; Cachet</div>
            </td>
            <td class="signature-cell" style="padding-left: 15px;">
                <div style="font-weight: bold; margin-bottom: 4px; font-size: 10px; text-transform: uppercase; color: #475569;">Accusé de Décharge Banque</div>
                <div class="signature-box">Tampon &amp; Signature Banque</div>
            </td>
        </tr>
    </table>

</body>
</html>
