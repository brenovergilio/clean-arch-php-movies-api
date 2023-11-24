<?php

namespace App\Presentation\Validations;
use App\Core\Domain\Exceptions\MissingRequiredFieldException;
use App\Presentation\Validations\Interfaces\Validation;
use Exception;

class RequiredFieldValidation implements Validation {
  public function __construct(private string $fieldName) {}

  public function validate($input): ?Exception {
    if(!isset($input[$this->fieldName])) return new MissingRequiredFieldException($this->fieldName);
    return null;
  }
}