<?php

namespace App\Presentation\Validations;
use App\Core\Domain\Exceptions\WeakPasswordException;
use App\Presentation\Validations\Interfaces\Validation;
use Exception;

class PasswordValidation implements Validation {
  const regex = "/^(?=.*[A-Z])(?=.*[a-z])(?=.*\W).{8,}$/";
  public function __construct(private string $fieldName) {}

  public function validate($input): ?Exception {
    $isValid = preg_match(PasswordValidation::regex, $input[$this->fieldName]);
    if(!$isValid) return new WeakPasswordException();
    return null;
  }
}