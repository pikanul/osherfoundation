<?php

namespace App\Jobs;

use App\Mail\ContentUpdateMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendContentUpdateChunkJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    public int $timeout = 120;

    public function __construct(
        public array $payload,
        public array $emails,
        public int $pauseMilliseconds = 200
    ) {}

    public function handle(): void
    {
        if (empty($this->emails)) {
            return;
        }

        if (function_exists('setMailConfig')) {
            setMailConfig();
        }

        foreach ($this->emails as $email) {
            try {
                Mail::mailer('dynamic')->to($email)->send(new ContentUpdateMail($this->payload, $email));
            } catch (\Throwable $e) {
                Log::warning('Failed sending content update email (chunk)', [
                    'type' => $this->payload['type'] ?? null,
                    'content_title' => $this->payload['title'] ?? null,
                    'email' => $email,
                    'error' => $e->getMessage(),
                ]);
            }

            if ($this->pauseMilliseconds > 0) {
                usleep($this->pauseMilliseconds * 1000);
            }
        }
    }
}

