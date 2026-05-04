<?php

namespace App\Mail;

use App\Models\BreakdownLog;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class BreakdownReportedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $log;

    /**
     * Create a new message instance.
     */
    public function __construct(BreakdownLog $log)
    {
        $this->log = $log;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'URGENT: Laporan Kerusakan Baru - ' . $this->log->infrastructure->code_name,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.breakdown_reported',
        );
    }
}
