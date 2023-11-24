<?php

namespace App\Presentation\Validations;
use App\Presentation\Interfaces\Validation;
use Exception;

class ValidationComposite implements Validation {
  public function __construct(
    private array $validations
  ) {}

  public function validate($input): ?Exception {
    
    foreach($this->validations as $validation) {
      $exception = $validation->validate($input);
      if($exception) return $exception;
    }

    return null;
  }
}