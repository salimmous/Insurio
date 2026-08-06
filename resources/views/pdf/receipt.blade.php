<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Reçu d'Encaissement {{ $ledger->receipt_number ?? $ledger->transaction_id }}</title>
    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size: 12px; color: #1e293b; margin: 0; padding: 25px; }
        .header { border-bottom: 2px solid #0284c7; padding-bottom: 15px; margin-bottom: 20px; }
        .agency-name { font-size: 20px; font-weight: bold; color: #0f172a; text-transform: uppercase; }
        .agency-info { font-size: 10px; color: #64748b; margin-top: 4px; }
        .receipt-title { text-align: center; margin: 20px 0; background: #f8fafc; border: 1px solid #e2e8f0; padding: 12px; border-radius: 8px; }
        .receipt-title h1 { font-size: 18px; margin: 0; color: #0284c7; text-transform: uppercase; letter-spacing: 1px; }
        .receipt-title p { font-size: 11px; margin: 4px 0 0 0; font-family: monospace; font-weight: bold; color: #334155; }
        .meta-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .meta-table td { padding: 8px 12px; border-bottom: 1px solid #f1f5f9; }
        .label { font-weight: bold; color: #475569; width: 35%; font-size: 11px; }
        .value { color: #0f172a; font-size: 12px; }
        .amount-box { background: #ecfdf5; border: 2px solid #10b981; padding: 15px; border-radius: 10px; text-align: center; margin: 25px 0; }
        .amount-val { font-size: 24px; font-weight: bold; color: #059669; font-family: monospace; }
        .footer-stamps { margin-top: 40px; width: 100%; }
        .stamp-box { width: 45%; float: left; text-align: center; border: 1px dashed #cbd5e1; padding: 25px; min-height: 80px; border-radius: 8px; font-size: 10px; color: #94a3b8; }
        .clear { clear: both; }
    </style>
</head>
<body>
    <div class="header">
        <div class="agency-name">{{ $agencyName }}</div>
        <div class="agency-info">{{ $agencyAddress }} | Tel: {{ $agencyPhone }} | Email: {{ $agencyEmail }}</div>
    </div>

    <div class="receipt-title">
        <h1>REÇU DE PAIEMENT / QUITTANCE</h1>
        <p>N° REÇU: {{ $ledger->receipt_number ?? $ledger->transaction_id }} | DATE: {{ $ledger->entry_date ? \Carbon\Carbon::parse($ledger->entry_date)->format('d/m/Y H:i') : now()->format('d/m/Y H:i') }}</p>
    </div>

    <table class="meta-table">
        <tr>
            <td class="label">N° Transaction Grand Livre:</td>
            <td class="value" style="font-family: monospace; font-weight: bold;">{{ $ledger->transaction_id }}</td>
        </tr>
        <tr>
            <td class="label">Client Émetteur:</td>
            <td class="value">
                @if($ledger->client)
                    <strong>{{ $ledger->client->first_name }} {{ $ledger->client->last_name }}</strong> (CIN: {{ $ledger->client->cin ?? '-' }})
                @else
                    Opération Générale D'Agence
                @endif
            </td>
        </tr>
        <tr>
            <td class="label">Motif / Libellé:</td>
            <td class="value">{{ $ledger->notes ?: 'Règlement Opération d\'Assurance' }}</td>
        </tr>
        <tr>
            <td class="label">Mode de Règlement:</td>
            <td class="value" style="text-transform: uppercase; font-weight: bold;">
                @if($ledger->payment_method === 'cash')
                    Espèces (Caisse Agence)
                @elseif($ledger->payment_method === 'cheque')
                    Chèque Marocain (N° {{ $ledger->cheque_number ?? '-' }})
                @elseif($ledger->payment_method === 'transfer')
                    Virement Bancaire
                @else
                    Carte / TPE
                @endif
            </td>
        </tr>
        <tr>
            <td class="label">Statut du Règlement:</td>
            <td class="value" style="color: #059669; font-weight: bold;">VALIDÉ & COMPTABILISÉ</td>
        </tr>
    </table>

    <div class="amount-box">
        <div style="font-size: 11px; text-transform: uppercase; font-weight: bold; color: #047857; margin-bottom: 5px;">MONTANT ENCAISSÉ</div>
        <div class="amount-val">{{ number_format($ledger->amount, 2) }} DH</div>
    </div>

    <div class="footer-stamps">
        <div class="stamp-box">
            Signature / Cachet du Client
        </div>
        <div class="stamp-box" style="float: right;">
            Cachet & Signature Agence<br><br>
            <strong style="color: #475569;">{{ $agencyName }}</strong>
        </div>
        <div class="clear"></div>
    </div>
</body>
</html>
