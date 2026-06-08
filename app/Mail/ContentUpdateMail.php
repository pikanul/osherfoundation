<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ContentUpdateMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public array $payload,
        public string $email
    ) {}

    public function envelope(): Envelope
    {
        $subjectPrefix = ($this->payload['type'] ?? '') === 'news' ? 'News Update' : 'Blog Update';

        return new Envelope(
            subject: $subjectPrefix . ': ' . ($this->payload['title'] ?? 'New Update')
        );
    }

    public function content(): Content
    {
        $view = ($this->payload['type'] ?? '') === 'news'
            ? 'mail.news_alert'
            : 'mail.content_update';

        return new Content(
            view: $view,
            with: [
                'payload' => $this->payload,
                'md5email' => md5($this->email),
            ]
        );
    }
}
