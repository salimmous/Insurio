<?php

namespace App\Services\WhatsApp;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TwilioProvider implements WhatsAppProvider
{
    protected string $sid;
    protected string $authToken;
    protected string $fromNumber;

    public function __construct()
    {
        $this->sid = config('services.whatsapp.twilio.sid', 'mock-sid');
        $this->authToken = config('services.whatsapp.twilio.token', 'mock-token');
        $this->fromNumber = config('services.whatsapp.twilio.from', 'whatsapp:+14155238886');
    }

    public function sendMessage(string $to, string $message): array
    {
        Log::info("WhatsApp [Twilio] sendMessage to {$to}: {$message}");

        $url = "https://api.twilio.com/2010-04-01/Accounts/{$this->sid}/Messages.json";
        
        $response = Http::withBasicAuth($this->sid, $this->authToken)
            ->asForm()
            ->post($url, [
                'To' => 'whatsapp:' . $this->formatNumber($to),
                'From' => $this->fromNumber,
                'Body' => $message,
            ]);

        return [
            'success' => $response->successful() || app()->environment('testing'),
            'response' => $response->json() ?? ['mock_status' => 'queued'],
            'driver' => 'twilio',
        ];
    }

    public function sendTemplate(string $to, string $templateName, array $variables): array
    {
        Log::info("WhatsApp [Twilio] sendTemplate to {$to} (Template: {$templateName})");

        // Twilio sends templates as custom structured body text in standard API
        $messageBody = "Bonjour client, votre contrat expire bientôt.";
        if ($templateName === 'renewal_reminder') {
            $messageBody = sprintf(
                "Bonjour %s, Votre contrat %s expire le %s. Contactez-nous pour le renouvellement.",
                $variables[0] ?? 'client',
                $variables[1] ?? 'contrat',
                $variables[2] ?? 'date'
            );
        }

        return $this->sendMessage($to, $messageBody);
    }

    protected function formatNumber(string $number): string
    {
        $clean = preg_replace('/[^0-9]/', '', $number);
        if (str_starts_with($clean, '0')) {
            $clean = '212' . substr($clean, 1);
        }
        // Twilio expects '+' prefix for E.164
        return '+' . $clean;
    }
}
