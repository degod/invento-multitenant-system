<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class MailClient extends Mailable
{
    use Queueable, SerializesModels;

    public string $body;

    /**
     * Create a new message instance.
     */
    public function __construct(string $subject, string $body, ?string $from = null)
    {
        $this->subject($subject);
        $this->body = $body;

        if ($from) {
            $this->from($from);
        }
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->view('emails.template')
            ->with(['body' => $this->body]);
    }
}
