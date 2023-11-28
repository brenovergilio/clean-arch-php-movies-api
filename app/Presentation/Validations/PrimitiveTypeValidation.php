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
        if (!is_string($field)) return new InvalidFieldException($this->fieldName);
          break;
      case 'int':
          if (filter_var($field, FILTER_VALIDATE_INT) === false) return new InvalidFieldException($this->fieldName);
          break;
      case 'float':
          if (filter_var($field, FILTER_VALIDATE_FLOAT) === false) return new InvalidFieldException($this->fieldName);
          break;
      case 'bool':
          if (!is_bool($field) && strcasecmp($field, "true") !== 0 && strcasecmp($field, "false") !== 0) return new InvalidFieldException($this->fieldName);
          break;
      default:
          return null;
    }

    return null;
  }
}