<?php

namespace App\Presentation\Validations\Adapters;
use App\Presentation\Validations\Interfaces\IsEnumValidator;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class IsEnumValidatorAdapter implements IsEnumValidator {
  public function isValid(string $enum, string $enumName): bool {
    $validator = Validator::make(['enum' => $enum], [
      'enum' => [Rule::enum($enumName)]
    ]);

    return $validator->passes();
  }
}