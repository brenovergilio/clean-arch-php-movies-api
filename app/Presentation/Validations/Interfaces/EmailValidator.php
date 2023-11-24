<?php

namespace App\Presentation\Validations\Interfaces;

interface EmailValidator {
  public function isValid(string $email): bool;
}