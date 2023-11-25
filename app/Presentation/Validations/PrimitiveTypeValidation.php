<?php

namespace App\Presentation\Validations;
use App\Core\Domain\Exceptions\InvalidFieldException;
use App\Presentation\Validations\Interfaces\Validation;
use Exception;

class PrimitiveTypeValidation implements Validation {
  public function __construct(private string $fieldName, private string $primitiveType) {}

  public function validate($input): ?Exception {
    $field = $input[$this->fieldName];

    switch ($this->primitiveType) {
      case 'string':
          if(!is_string($field)) return new InvalidFieldException($this->fieldName);
          break;
      case 'int':
          if(!is_int($field)) return new InvalidFieldException($this->fieldName);
          break;
      case 'float':
          if(!is_float($field)) return new InvalidFieldException($this->fieldName);
          break;
      case 'bool':
          if(!is_bool($field)) return new InvalidFieldException($this->fieldName);
          break;
      default:
          return null;
    }

    return null;
  }
}