<?php

namespace App\Presentation\Validations;
use App\Core\Domain\Exceptions\InvalidFieldException;
use App\Presentation\Validations\Interfaces\Validation;
use Exception;

class PositiveNumberValidation implements Validation {
  public function __construct(private string $fieldName) {}

  public function validate($input): ?Exception {
    $isValid = floatval($input[$this->fieldName]) > 0;
    if(!$isValid) return new InvalidFieldException($this->fieldName);
    return null;
  }
}