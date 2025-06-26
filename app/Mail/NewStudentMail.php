<?php

namespace App\Mail;

use App\Models\Application;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NewStudentMail extends Mailable
{
    use Queueable, SerializesModels;

    public mixed $data;

    public function __construct(mixed $data)
    {
        $this->data = $data;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address(config('mail.from.address'), Application::first()->name),
            subject: 'Data Siswa Baru',
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.new-student',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
