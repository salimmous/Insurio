<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Rapport d'Audit de Sécurité Enterprise - Insurio</title>
    <style>
        @page {
            size: A4 landscape;
            margin: 12mm;
        }
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #0f172a;
            font-size: 10px;
            line-height: 1.4;
            background: #fff;
        }
        .header-table {
            width: 100%;
            border-bottom: 2px solid #334155;
            padding-bottom: 12px;
            margin-bottom: 15px;
        }
        .logo-title {
            font-size: 18px;
            font-weight: 900;
            color: #0f172a;
            letter-spacing: -0.5px;
        }
        .subtitle {
            font-size: 9px;
            color: #475569;
            font-weight: bold;
            text-transform: uppercase;
        }
        .meta-box {
            text-align: right;
            font-size: 9px;
            color: #475569;
        }
        .badge-immutability {
            background: #0f172a;
            color: #38bdf8;
            font-size: 8px;
            font-weight: bold;
            padding: 3px 8px;
            border-radius: 4px;
            display: inline-block;
            margin-top: 4px;
        }
        table.audit-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        table.audit-table th {
            background: #0f172a;
            color: #f8fafc;
            text-align: left;
            padding: 6px 8px;
            font-size: 8px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        table.audit-table td {
            border-bottom: 1px solid #e2e8f0;
            padding: 6px 8px;
            font-size: 8px;
            vertical-align: top;
        }
        table.audit-table tr:nth-child(even) {
            background: #f8fafc;
        }
        .status-success {
            color: #15803d;
            font-weight: bold;
        }
        .status-failed, .status-critical {
            color: #b91c1c;
            font-weight: bold;
        }
        .status-warning {
            color: #b45309;
            font-weight: bold;
        }
        .font-mono {
            font-family: monospace;
        }
        .footer {
            margin-top: 20px;
            border-top: 1px solid #e2e8f0;
            padding-top: 8px;
            text-align: center;
            font-size: 8px;
            color: #64748b;
        }
    </style>
</head>
<body @if(!empty($isPrint)) onload="window.print()" @endif>

    <table class="header-table">
        <tr>
            <td>
                <div class="logo-title">🔒 CENTRE D'AUDIT DE SÉCURITÉ ENTERPRISE</div>
                <div class="subtitle">{{ $agencyName }} • Registre Inaltérable d'Événements</div>
                <div class="badge-immutability">LEDGER IMMUABLE • CONFORMITÉ AUDIT CRITICAL</div>
            </td>
            <td class="meta-box">
                <div><strong>Généré le:</strong> {{ $generatedAt }}</div>
                <div><strong>Opérateur:</strong> {{ $generatedBy }}</div>
                <div><strong>Total Enregistrements:</strong> {{ count($logs) }}</div>
            </td>
        </tr>
    </table>

    <table class="audit-table">
        <thead>
            <tr>
                <th style="width: 14%;">UUID / Timestamp</th>
                <th style="width: 15%;">Utilisateur</th>
                <th style="width: 12%;">Agence / Branche</th>
                <th style="width: 10%;">Rôle</th>
                <th style="width: 12%;">IP / Appareil</th>
                <th style="width: 14%;">Type d'Événement</th>
                <th style="width: 8%;">Statut</th>
                <th style="width: 15%;">Notes & Détails</th>
            </tr>
        </thead>
        <tbody>
            @forelse($logs as $log)
                <tr>
                    <td class="font-mono">
                        <strong style="color: #0284c7;">{{ substr($log->uuid, 0, 13) }}...</strong><br>
                        <span style="color: #64748b; font-size: 7.5px;">{{ $log->created_at ? $log->created_at->format('d/m/Y H:i:s') : '' }}</span>
                    </td>
                    <td>
                        <strong>{{ $log->user_name }}</strong><br>
                        <span style="color: #64748b; font-size: 7.5px;">{{ $log->user_email }}</span>
                    </td>
                    <td>
                        {{ $log->agency_name }}<br>
                        <span style="color: #64748b; font-size: 7.5px;">{{ $log->branch_name }}</span>
                    </td>
                    <td>{{ $log->role_name }}</td>
                    <td class="font-mono">
                        {{ $log->ip_address }}<br>
                        <span style="color: #64748b; font-size: 7.5px;">{{ $log->browser }} ({{ $log->os }})</span>
                    </td>
                    <td>
                        <strong style="text-transform: uppercase; font-size: 7.5px;">{{ $log->event_type }}</strong>
                    </td>
                    <td>
                        <span class="status-{{ $log->status }}">
                            {{ strtoupper($log->status) }}
                        </span>
                    </td>
                    <td>{{ $log->notes }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" style="text-align: center; padding: 20px; color: #94a3b8;">
                        Aucun événement de sécurité trouvé dans ce registre.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        Document d'audit officiel généré par le système Insurio Security Core Engine. Certification d'horodatage et d'intégrité garantie.
    </div>

</body>
</html>
