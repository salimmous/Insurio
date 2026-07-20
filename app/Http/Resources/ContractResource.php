<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ContractResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'contract_number' => $this->contract_number,
            'policy_number' => $this->policy_number,
            'start_date' => $this->start_date ? $this->start_date->format('Y-m-d') : null,
            'end_date' => $this->end_date ? $this->end_date->format('Y-m-d') : null,
            'premium_amount' => $this->premium_amount,
            'commission_amount' => $this->commission_amount,
            'payment_status' => $this->payment_status,
            'status' => $this->status,
            'client_id' => $this->client_id,
            'insurance_company_id' => $this->insurance_company_id,
            'insurance_type_id' => $this->insurance_type_id,
            'details_type' => $this->details_type,
            'details' => $this->details,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
