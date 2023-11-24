<?php

namespace App\Presentation\Validations\Adapters;
use App\Presentation\Validations\Interfaces\CPFValidator;
use Illuminate\Support\Facades\Validator;

class CPFValidatorAdapter implements CPFValidator {
  public function isValid(string $cpf): bool {
    $validator = Validator::make(['cpf' => $cpf], [
      'cpf' => 'cpf'
    ]);

    return $validator->passes();
  }
}