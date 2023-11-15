<?php

namespace App\Core\Application\Exceptions;
use Exception;

class DuplicatedUniqueFieldException extends Exception
{
  public function __construct($fieldName) {
    parent::__construct("Field $fieldName already exists");
}
}