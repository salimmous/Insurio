<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Contract;
use Illuminate\Http\Request;

class ClientPortalController extends Controller
{
    public function show(string $token)
    {
        // Try finding contract by uuid or contract_number or decrypted id
        $contract = Contract::where('uuid', $token)
            ->orWhere('contract_number', $token)
            ->first();

        if (!$contract) {
            abort(404, 'Attestation ou contrat introuvable.');
        }

        return view('tenant.portal', [
            'contract' => $contract,
            'client' => $contract->client,
            'compagnie' => $contract->compagnie,
        ]);
    }
}
