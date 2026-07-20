<?php

namespace App\Imports;

use App\Models\Contract;
use App\Models\Client;
use App\Models\ContratAuto;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Carbon\Carbon;

class ContractsImport implements ToModel, WithHeadingRow, WithValidation
{
    protected array $rowErrors = [];
    protected int $successCount = 0;
    protected int $failedCount = 0;

    public function model(array $row)
    {
        try {
            // Find client
            $clientEmail = $row['client_email'] ?? $row['email_client'] ?? null;
            $clientName = $row['client_name'] ?? $row['nom_client'] ?? null;
            $client = null;

            if ($clientEmail) {
                $client = Client::where('email', $clientEmail)->first();
            }

            if (!$client && $clientName) {
                $parts = explode(' ', $clientName, 2);
                $client = Client::where('last_name', 'like', '%' . ($parts[0] ?? '') . '%')->first();
            }

            if (!$client) {
                // Create a temporary client if none exists
                $client = Client::create([
                    'first_name' => $clientName ?? 'Client',
                    'last_name' => 'Importé',
                    'email' => $clientEmail ?? 'import_' . uniqid() . '@example.com',
                    'client_type' => 'individual',
                ]);
            }

            $startDateStr = $row['start_date'] ?? $row['date_debut'] ?? now()->toDateString();
            $endDateStr = $row['end_date'] ?? $row['date_fin'] ?? now()->addYear()->toDateString();

            // Support Excel Date formats or raw strings
            $startDate = is_numeric($startDateStr) ? Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($startDateStr)) : Carbon::parse($startDateStr);
            $endDate = is_numeric($endDateStr) ? Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($endDateStr)) : Carbon::parse($endDateStr);

            // Create ContratAuto or Contract
            $contract = new ContratAuto([
                'client_id' => $client->id,
                'details_type' => \App\Models\AutoContractDetails::class, // default details type
                'details_id' => 1, // placeholder or null
                'contract_number' => $row['contract_number'] ?? $row['numero_contrat'] ?? 'CN-' . strtoupper(uniqid()),
                'policy_number' => $row['policy_number'] ?? $row['police'] ?? null,
                'start_date' => $startDate->toDateString(),
                'end_date' => $endDate->toDateString(),
                'premium_amount' => (float)($row['premium_amount'] ?? $row['prime'] ?? 0.00),
                'commission_amount' => (float)($row['commission_amount'] ?? $row['commission'] ?? 0.00),
                'payment_status' => $row['payment_status'] ?? $row['statut_paiement'] ?? 'pending',
                'created_by' => auth()->id(),
            ]);

            $this->successCount++;
            return $contract;
        } catch (\Throwable $e) {
            $this->failedCount++;
            $this->rowErrors[] = "Erreur de ligne: " . $e->getMessage();
            return null;
        }
    }

    public function rules(): array
    {
        return [];
    }

    public function getSuccessCount(): int
    {
        return $this->successCount;
    }

    public function getFailedCount(): int
    {
        return $this->failedCount;
    }

    public function getErrors(): array
    {
        return $this->rowErrors;
    }
}
