<?php

namespace App\Presentation\Validations\Adapters;
use App\Presentation\Validations\Interfaces\EmailValidator;
use Illuminate\Support\Facades\Validator;

class EmailValidatorAdapter implements EmailValidator {
  public function isValid(string $email): bool {
    $validator = Validator::make(['email' => $email], [
      'email' => 'email'
    ]);

    return $validator->passes();
  }
}