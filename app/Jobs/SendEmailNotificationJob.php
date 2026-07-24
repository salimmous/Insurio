<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Throwable;

class SendEmailNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    public int $timeout = 60;

    protected string $recipientEmail;
    protected string $subject;
    protected string $view;
    protected array $data;

    public function __construct(string $recipientEmail, string $subject, string $view, array $data = [])
    {
        $this->recipientEmail = $recipientEmail;
        $this->subject = $subject;
        $this->view = $view;
        $this->data = $data;
    }

    public function handle(): void
    {
        Log::info("Processing queued email notification to: {$this->recipientEmail} [{$this->subject}]");

        try {
            Mail::send($this->view, $this->data, function ($message) {
                $message->to($this->recipientEmail)
                    ->subject($this->subject);
            });

            Log::info("Queued email notification sent successfully to {$this->recipientEmail}");
        } catch (Throwable $e) {
            Log::error("Failed to send queued email notification to {$this->recipientEmail}: " . $e->getMessage());
            throw $e;
        }
    }

    public function failed(Throwable $exception): void
    {
        Log::critical("SendEmailNotificationJob permanently failed for {$this->recipientEmail}: " . $exception->getMessage());
    }
}
