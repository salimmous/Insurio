<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Contract;
use App\Models\AutoContractDetail;
use App\Models\HealthContractDetail;
use App\Models\TravelContractDetail;
use App\Http\Resources\ContractResource;

class ApiContractController extends Controller
{
    public function index(Request $request)
    {
        $query = Contract::with(['client', 'compagnie', 'product', 'details']);

        if ($request->has('search')) {
            $search = $request->query('search');
            $query->where('contract_number', 'like', "%{$search}%")
                  ->orWhere('policy_number', 'like', "%{$search}%");
        }

        if ($request->has('client_id')) {
            $query->where('client_id', $request->query('client_id'));
        }

        if ($request->has('status')) {
            $query->where('status', $request->query('status'));
        }

        $contracts = $query->latest()->paginate($request->query('per_page', 15));

        return ContractResource::collection($contracts);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'insurance_company_id' => 'required|exists:compagnies,id',
            'insurance_type_id' => 'required|exists:products,id',
            'contract_number' => 'required|string|unique:contracts,contract_number',
            'policy_number' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'premium_amount' => 'required|numeric|min:0',
            'commission_amount' => 'required|numeric|min:0',
            'payment_status' => 'required|in:paid,pending,partial',
            'status' => 'required|in:active,expired,cancelled,pending',
            'succursale_id' => 'nullable|integer',
            'details_type' => 'required|in:auto,health,travel',
            'details' => 'required|array',
        ]);

        // Create polymorphic detail record based on details_type
        $detailModel = null;
        if ($validated['details_type'] === 'auto') {
            $detailModel = AutoContractDetail::create($validated['details']);
            $detailsClass = AutoContractDetail::class;
        } elseif ($validated['details_type'] === 'health') {
            $detailModel = HealthContractDetail::create($validated['details']);
            $detailsClass = HealthContractDetail::class;
        } else {
            $detailModel = TravelContractDetail::create($validated['details']);
            $detailsClass = TravelContractDetail::class;
        }

        $contractData = array_merge($validated, [
            'details_type' => $detailsClass,
            'details_id' => $detailModel->id,
            'numero_contrat' => $validated['contract_number'], // Compatibility
            'police' => $validated['policy_number'], // Compatibility
            'date_effet' => $validated['start_date'], // Compatibility
            'date_echeance' => $validated['end_date'], // Compatibility
            'prime_totale' => $validated['premium_amount'], // Compatibility
            'compagnie_id' => $validated['insurance_company_id'], // Compatibility
            'product_id' => $validated['insurance_type_id'], // Compatibility
            'statut' => $validated['status'], // Compatibility
        ]);

        $contract = Contract::create($contractData);

        return new ContractResource($contract->load('details'));
    }
}
