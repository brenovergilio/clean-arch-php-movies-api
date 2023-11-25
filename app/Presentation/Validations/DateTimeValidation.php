<?php

namespace App\Presentation\Validations;
use App\Core\Domain\Exceptions\InvalidFieldException;
use App\Presentation\Validations\Interfaces\DateTimeValidator;
use App\Presentation\Validations\Interfaces\Validation;
use Exception;

class DateTimeValidation implements Validation {
  public function __construct(private string $fieldName, private DateTimeValidator $dateTimeValidator) {}

  public function validate($input): ?Exception {
    $isValid = $this->dateTimeValidator->isValid($input[$this->fieldName]);
    if(!$isValid) return new InvalidFieldException($this->fieldName);
    return null;
  }
}