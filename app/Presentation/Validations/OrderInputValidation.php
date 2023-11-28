<?php

namespace App\Presentation\Validations;
use App\Presentation\Validations\Exceptions\InvalidOrderInputException;
use App\Presentation\Validations\Interfaces\Validation;
use Exception;

class OrderInputValidation implements Validation {
  const regex = "/^(-?[a-zA-Z]+,)*-?[a-zA-Z]+$/";
  public function __construct(private string $fieldName) {}

  public function validate($input): ?Exception {
    $isValid = preg_match(OrderInputValidation::regex, $input[$this->fieldName]);
    if(!$isValid) return new InvalidOrderInputException();
    return null;
  }
}