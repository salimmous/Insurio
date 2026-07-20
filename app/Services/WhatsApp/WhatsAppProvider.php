<?php

namespace App\Services\WhatsApp;

interface WhatsAppProvider
{
    /**
     * Send a raw text message.
     */
    public function sendMessage(string $to, string $message): array;

    /**
     * Send a pre-approved template message.
     */
    public function sendTemplate(string $to, string $templateName, array $variables): array;
}
