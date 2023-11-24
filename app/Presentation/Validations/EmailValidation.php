<?php

namespace App\Presentation\Validations;
use App\Core\Domain\Exceptions\InvalidFieldException;
use App\Presentation\Validations\Interfaces\Validation;
use App\Presentation\Validations\Interfaces\EmailValidator;
use Exception;

class EmailValidation implements Validation {
  public function __construct(private string $fieldName, private EmailValidator $emailValidator) {}

  public function validate($input): ?Exception {
    $isValid = $this->emailValidator->isValid($input[$this->fieldName]);
    if(!$isValid) return new InvalidFieldException($this->fieldName);
    return null;
  }
}