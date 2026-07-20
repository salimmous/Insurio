<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\ContratAuto;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class SendEcheanceReminders extends Command
{
    protected $signature = 'app:send-echeance-reminders {days=30}';
    protected $description = 'Send automated reminders for contracts expiring in X days';

    public function handle()
    {
        $days = (int)$this->argument('days');
        $targetDate = Carbon::now()->addDays($days)->toDateString();

        $contrats = ContratAuto::whereDate('date_echeance', $targetDate)
            ->where('statut', 'actif')
            ->get();

        $this->info("Found {$contrats->count()} contracts expiring on {$targetDate}");

        foreach ($contrats as $contrat) {
            // Log the reminder (can be extended to email/SMS)
            Log::info("Echéance reminder sent for Contract #{$contrat->numero_contrat} (Client: {$contrat->client->nom_complet}) expiring on {$contrat->date_echeance}");
            $this->info("Reminder logged for contract: {$contrat->numero_contrat}");
        }

        return Command::SUCCESS;
    }
}
