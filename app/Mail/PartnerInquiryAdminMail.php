<?php

namespace App\Mail;

use App\Models\PartnerInquiry;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PartnerInquiryAdminMail extends Mailable
{
    use Queueable, SerializesModels;

    public PartnerInquiry $inquiry;

    public function __construct(PartnerInquiry $inquiry)
    {
        $this->inquiry = $inquiry;
    }

    public function build()
    {
        return $this->subject('New Partner With Us Inquiry - OSHE Foundation')
            ->view('mail.partner_inquiry_admin')
            ->with([
                'inquiry' => $this->inquiry,
                'adminUrl' => route('admin.partner-inquiries.show', $this->inquiry->id),
            ]);
    }
}
