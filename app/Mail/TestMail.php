<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TestMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */

     public $mailInfo;

     public function __construct($mailInfo)
     {

         $this->mailInfo = $mailInfo;

     }


    /**
     * Get the message envelope.
     */


     public function envelope(): Envelope
     {
         return new Envelope(
             subject : $this->mailInfo['subject'],
         );
     }


    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'testmail',
            with:[
                'mailInfo_data' => $this->mailInfo,
            ],
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
