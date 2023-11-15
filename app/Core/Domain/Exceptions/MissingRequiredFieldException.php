<?php

namespace App\Core\Domain\Exceptions;
use Exception;

class MissingRequiredFieldException extends Exception {
  public function __construct($fieldName) {
    parent::__construct("Field $fieldName is missing");
  }
}