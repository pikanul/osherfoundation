<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class StudentCheckInOut extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    private $mail_info = [];
    public function __construct($mail_info)
    {
        $this->mail_info = $mail_info;
        $this->mail_info['appname'] = settings('app_title', 9);
        $this->mail_info['status_text'] = ($this->mail_info['status'] == 1) ? 'Check In' : 'Check Out';

    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->mail_info['name'] . ' Check In Out Notification '. date('d-M-Y'),
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'theme5.student_check_in_out',
            with: [ 'mail_info' => $this->mail_info]
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
