<?php

namespace App\Infra\Mail;
use App\Core\Application\Interfaces\EmailSender;
use App\Jobs\SendEmailJob;
use App\Mail\ConfirmAccountMail;

class ConfirmAccountEmailSender implements EmailSender
{
  public function sendMail(string $to, string $subject, $data): void
  {
    $mailableObject = new ConfirmAccountMail($to, $subject, $data);
    dispatch(new SendEmailJob($mailableObject));
  }
}