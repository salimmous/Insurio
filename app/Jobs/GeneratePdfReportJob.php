<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use App\Services\CloudStorageService;
use Throwable;

class GeneratePdfReportJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    public int $timeout = 120;
    public bool $deleteWhenMissingModels = true;

    protected string $reportType;
    protected array $data;
    protected ?string $tenantId;

    public function __construct(string $reportType, array $data, ?string $tenantId = null)
    {
        $this->reportType = $reportType;
        $this->data = $data;
        $this->tenantId = $tenantId ?? (function_exists('tenant') && tenant('id') ? tenant('id') : null);
    }

    public function handle(): void
    {
        Log::info("Starting queued PDF generation job [{$this->reportType}] for tenant [{$this->tenantId}]");

        if ($this->tenantId && function_exists('tenancy')) {
            $tenant = \App\Models\Tenant::find($this->tenantId);
            if ($tenant) {
                tenancy()->initialize($tenant);
            }
        }

        try {
            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView("pdf.{$this->reportType}", $this->data);
            $filename = "reports/{$this->reportType}_" . time() . ".pdf";
            
            $storedPath = CloudStorageService::putFile($filename, $pdf->output());

            Log::info("Successfully generated queued PDF report: {$storedPath}");
        } catch (Throwable $e) {
            Log::error("Failed to generate queued PDF report [{$this->reportType}]: " . $e->getMessage(), [
                'exception' => $e
            ]);
            throw $e;
        }
    }

    public function failed(Throwable $exception): void
    {
        Log::critical("GeneratePdfReportJob permanently failed for report [{$this->reportType}]: " . $exception->getMessage());
    }
}
