<?php

namespace App\Services;

use MailerSend\LaravelDriver\MailerSend;

class MailerSendService
{
    protected $mailerSend;

    public function __construct()
    {
        $this->mailerSend = new MailerSend(['api_key' => env('MAILERSEND_API_KEY')]);
    }

    public function sendEmail($to, $subject, $textContent, $htmlContent, $from = null)
    {
        $email = $this->mailerSend->email->new()
            ->setFrom($from ?? ['email' => 'your-default-email@example.com', 'name' => 'Your Name'])
            ->addTo($to)
            ->setSubject($subject)
            ->setHtml($htmlContent)
            ->setText($textContent);

        return $this->mailerSend->email->send($email);
    }
}
