<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use App\Models\CampaignEmail;
use App\Mail\CampaignEmailMail;
use App\Models\CampaignEmailSingle;

class SendCampaignChunkJob implements ShouldQueue
{
    use Dispatchable, Queueable, SerializesModels;

    public function __construct(
        public int $campaignId,
        public array $emailIds
    ) {}


    public function handle()
    {
        $emails = CampaignEmailSingle::whereIn('id', $this->emailIds)->get();
        $campaign = CampaignEmail::find($this->campaignId);
        setMailConfig();
        foreach ($emails as $emailRow) {
            try {
                Mail::mailer('dynamic')->to($emailRow->email)->send(
                    new CampaignEmailMail($campaign, $emailRow)
                );

                $emailRow->update(['status' => 'sent']);
                CampaignEmail::whereId($campaign->id)->increment('sent');

            } catch (\Throwable $e) {

                $msg = strtolower($e->getMessage());
                $status = 'failed';

                if (str_contains($msg, 'spamhaus') || str_contains($msg, 'blocked')) {
                    $status = 'blocked';
                    CampaignEmail::whereId($campaign->id)->increment('blocked');
                } elseif (str_contains($msg, 'address rejected') || str_contains($msg, 'invalid')) {
                    $status = 'invalid';
                    CampaignEmail::whereId($campaign->id)->increment('invalid');
                } else {
                    CampaignEmail::whereId($campaign->id)->increment('failed');
                }

                $emailRow->update([
                    'status' => $status,
                    'error_message' => $e->getMessage(),
                ]);
            }
        }
    }
}