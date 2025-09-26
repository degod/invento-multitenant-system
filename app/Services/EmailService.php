<?php

namespace App\Services;

use Illuminate\Support\Facades\Mail;
use App\Mail\MailClient;

class EmailService
{
    public function send(string $to, string $subject, string $body): void
    {
        $from = config('mail.from.address');
        Mail::to($to)->send(new MailClient($subject, $body, $from));
    }

    public function sendBillNotification(string $to, array $billData, $flat, $building): void
    {
        $subject = 'New Bill Notification';
        $body = "A new bill has been created with the following details:\n\n <br><br>" .
            "<b>Category:</b> {$billData['category_name']}<br>" .
            "<b>Amount:</b> $" . number_format($billData['amount'], 2) . "<br>" .
            "<b>Month:</b> {$billData['month']}<br>" .
            "<b>Building:</b> {$building->name}<br>" .
            "<b>Address:</b> {$building->address}<br>" .
            "<b>Flat:</b> {$flat->flat_number}<br><hr>" .
            "Please log in to your account to view more details.";

        $this->send($to, $subject, $body);
    }
}
