<?php

namespace App\Imports;

use App\Models\Client;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class ClientsImport implements ToModel, WithHeadingRow, WithValidation
{
    protected array $rowErrors = [];
    protected int $successCount = 0;
    protected int $failedCount = 0;

    public function model(array $row)
    {
        try {
            // Flexible mapping of keys
            $firstName = $row['first_name'] ?? $row['prenom'] ?? '';
            $lastName = $row['last_name'] ?? $row['nom'] ?? '';
            $email = $row['email'] ?? null;
            $phone = $row['phone'] ?? $row['telephone'] ?? null;
            $clientType = $row['client_type'] ?? $row['type'] ?? 'individual';
            $companyName = $row['company_name'] ?? $row['nom_entreprise'] ?? null;
            $cin = $row['cin'] ?? null;

            if (empty($firstName) && empty($lastName) && empty($companyName)) {
                throw new \Exception("Row is empty or has missing names.");
            }

            $client = new Client([
                'first_name' => $firstName,
                'last_name' => $lastName,
                'email' => $email,
                'phone' => $phone,
                'client_type' => $clientType === 'company' || $clientType === 'entreprise' ? 'company' : 'individual',
                'company_name' => $companyName,
                'cin' => $cin,
            ]);

            $this->successCount++;
            return $client;
        } catch (\Throwable $e) {
            $this->failedCount++;
            $this->rowErrors[] = "Erreur de ligne: " . $e->getMessage();
            return null;
        }
    }

    public function rules(): array
    {
        return [
            '*.email' => 'nullable|email',
        ];
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
