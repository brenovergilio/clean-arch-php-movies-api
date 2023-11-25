<?php

namespace App\Presentation\Validations;
use App\Core\Domain\Exceptions\InvalidFieldException;
use App\Presentation\Validations\Interfaces\IsEnumValidator;
use App\Presentation\Validations\Interfaces\Validation;
use Exception;

class IsEnumValidation implements Validation {
  public function __construct(private string $fieldName, private IsEnumValidator $isEnumValidator, private string $enumName) {}

  public function validate($input): ?Exception {
    $isValid = $this->isEnumValidator->isValid($input[$this->fieldName], $this->enumName);
    if(!$isValid) return new InvalidFieldException($this->fieldName);
    return null;
  }
}