<?php

namespace App\Services;

use App\Models\Client;
use App\Models\Contract;
use Illuminate\Support\Facades\Http;

class AiCopilotService
{
    /**
     * Generate structured response or advice about a client profile.
     */
    public static function generateClientAdvice(Client $client, string $prompt): string
    {
        $apiKey = env('GEMINI_API_KEY');

        // Compile rich context about the client & contracts
        $contracts = $client->contrats()->with('compagnie', 'product')->get();
        
        $context = "Client Name: {$client->first_name} {$client->last_name}\n";
        $context .= "Email: {$client->email}\n";
        $context .= "Phone: {$client->phone}\n";
        $context .= "Notes: {$client->notes}\n";
        $context .= "Active Contracts count: {$contracts->count()}\n";
        
        foreach ($contracts as $index => $contract) {
            $context .= "Contract " . ($index + 1) . ":\n";
            $context .= " - Number: {$contract->contract_number}\n";
            $context .= " - Company: " . ($contract->compagnie->nom ?? 'N/A') . "\n";
            $context .= " - Product: " . ($contract->product->nom ?? 'N/A') . "\n";
            $context .= " - Premium: {$contract->premium_amount} DH\n";
            $context .= " - Start Date: {$contract->start_date->toDateString()}\n";
            $context .= " - End Date: {$contract->end_date->toDateString()}\n";
            $context .= " - Status: {$contract->status}\n";
        }

        // If Gemini API Key is configured, make real API call!
        if ($apiKey) {
            try {
                $response = Http::withHeaders([
                    'Content-Type' => 'application/json'
                ])->post("https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent?key={$apiKey}", [
                    'contents' => [
                        [
                            'parts' => [
                                ['text' => "Tu es l'assistant IA Copilot d'Insurio, le meilleur logiciel de gestion d'assurance en Afrique. Voici le contexte du client:\n{$context}\n\nL'utilisateur te pose cette question:\n{$prompt}\nRéponds de manière professionnelle, concise et orientée business."]
                            ]
                        ]
                    ]
                ]);

                if ($response->successful()) {
                    $json = $response->json();
                    return $json['candidates'][0]['content']['parts'][0]['text'] ?? 'Désolé, aucune réponse générée.';
                }
            } catch (\Exception $e) {
                // Fallback on failure
            }
        }

        // High fidelity rules-based offline advisor fallback
        $prompt = strtolower($prompt);
        if (str_contains($prompt, 'mail') || str_contains($prompt, 'courriel')) {
            return "Bonjour,\n\nNous vous contactons concernant votre contrat chez Insurio. Votre contrat arrivant à échéance, nous vous invitons à nous contacter pour procéder au renouvellement.\n\nCordialement,\nL'équipe de gestion.";
        }

        if (str_contains($prompt, 'whatsapp') || str_contains($prompt, 'message')) {
            return "Bonjour {$client->first_name}, votre contrat arrive à échéance prochainement. Contactez-nous pour le renouveler au plus vite. Merci !";
        }

        if (str_contains($prompt, 'opportunité') || str_contains($prompt, 'vente') || str_contains($prompt, 'cross-sell')) {
            if ($contracts->count() === 1 && $contracts->first()->product && $contracts->first()->product->code === 'AUTO') {
                return "💡 Opportunité de Cross-selling : Ce client ne possède qu'un contrat automobile. Nous suggérons de lui proposer une assurance Multirisque Habitation (MRH) avec une réduction de 10% sur le package global.";
            }
            return "💡 Proposition de Multi-équipement : Proposer un avenant pour couvrir la protection juridique ou assistance routière premium.";
        }

        // General fallback summary
        return "Copilot Insurio centralise les données de {$client->first_name} {$client->last_name}. Actuellement, {$contracts->count()} contrat(s) actif(s) enregistré(s) pour un volume de prime global de " . number_format($contracts->sum('premium_amount'), 2) . " DH.";
    }
}
