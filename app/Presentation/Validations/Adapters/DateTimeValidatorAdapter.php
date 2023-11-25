<?php

namespace App\Presentation\Validations\Adapters;
use App\Presentation\Validations\Interfaces\DateTimeValidator;
use Illuminate\Support\Facades\Validator;

class DateTimeValidatorAdapter implements DateTimeValidator {
  public function isValid(string $date): bool {
    $validator = Validator::make(['date' => $date], [
      'date' => 'date'
    ]);

    return $validator->passes();
  }
}