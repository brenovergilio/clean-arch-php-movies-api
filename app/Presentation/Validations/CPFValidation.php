<?php

namespace App\Presentation\Validations;
use App\Core\Domain\Exceptions\InvalidFieldException;
use App\Presentation\Validations\Interfaces\Validation;
use App\Presentation\Validations\Interfaces\CPFValidator;
use Exception;

class CPFValidation implements Validation {
  public function __construct(private string $fieldName, private CPFValidator $cpfValidator) {}

  public function validate($input): ?Exception {
    $isValid = $this->cpfValidator->isValid($input[$this->fieldName]);
    if(!$isValid) return new InvalidFieldException($this->fieldName);
    return null;
  }
}