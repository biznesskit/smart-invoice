<?php

namespace App\Mail;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class DailyLogReportMail extends Mailable
{
    use Queueable, SerializesModels;

    public $logContents;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($logContents)
    {
        $this->logContents = $logContents;
    }

    /**
     * Get the message envelope.
     *
     * @return \Illuminate\Mail\Mailables\Envelope
     */
    public function envelope()
    {
        return new Envelope(
            subject: 'Daily Log Report ' . Carbon::now()->toFormattedDateString(),
        );
    }

    /**
     * Get the message content definition.
     *
     * @return \Illuminate\Mail\Mailables\Content
     */
    public function content()
    {
        return new Content(
            view: 'emails.reports.daily-log-report',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array
     */
    public function attachments()
    {
        $log_file = env('CLOUD_LOGS_DIR', 'api');
        $date = date('Y-m-d');
        $filename = "logs_{$log_file}_{$date}.txt";

        // Create an in-memory stream
        $stream = fopen('php://temp', 'r+');
        fwrite($stream, $this->logContents);
        rewind($stream);

        return [
            Attachment::fromData(fn () => stream_get_contents($stream), $filename)
            ->withMime('text/plain'),
        ];
    }
}
