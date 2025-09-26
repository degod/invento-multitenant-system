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
        $body = "A new bill has been created with the following details:\n\n" .
            "Category: {$billData['category_name']}\n" .
            "Amount: $" . number_format($billData['amount'], 2) . "\n" .
            "Month: {$billData['month']}\n" .
            "Flat: {$flat->number}, Building: {$building->name}, Address: {$building->address}\n\n" .
            "Please log in to your account to view more details.";

        $this->send($to, $subject, $body);
    }
}
