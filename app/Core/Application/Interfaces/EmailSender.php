<?php

namespace App\Core\Application\Interfaces;

interface EmailSender
{
  function sendMail(string $to, string $subject, $data): void;
}