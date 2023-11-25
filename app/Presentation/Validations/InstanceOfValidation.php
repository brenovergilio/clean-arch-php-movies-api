<?php

namespace App\Presentation\Validations;
use App\Core\Domain\Exceptions\InvalidFieldException;
use App\Core\Domain\Exceptions\WeakPasswordException;
use App\Presentation\Validations\Interfaces\Validation;
use Exception;

class InstanceOfValidation implements Validation {
  public function __construct(private string $fieldName, private string $className) {}

  public function validate($input): ?Exception {
    $isValid = $input[$this->fieldName] instanceof $this->className;
    if(!$isValid) return new InvalidFieldException($this->fieldName);
    return null;
  }
}