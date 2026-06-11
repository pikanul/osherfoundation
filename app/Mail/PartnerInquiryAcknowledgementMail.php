<?php

namespace App\Mail;

use App\Models\PartnerInquiry;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PartnerInquiryAcknowledgementMail extends Mailable
{
    use Queueable, SerializesModels;

    public PartnerInquiry $inquiry;

    public function __construct(PartnerInquiry $inquiry)
    {
        $this->inquiry = $inquiry;
    }

    public function build()
    {
        return $this->subject('Thank you for your partnership inquiry - OSHE Foundation')
            ->view('mail.partner_inquiry_acknowledgement')
            ->with([
                'inquiry' => $this->inquiry,
            ]);
    }
}
