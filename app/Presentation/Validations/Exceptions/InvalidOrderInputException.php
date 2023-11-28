<?php

namespace App\Presentation\Validations\Exceptions;
use Exception;

class InvalidOrderInputException extends Exception {
  public function __construct() {
    parent::__construct("Invalid ordering input. It should be a comma-separated string with field names for ordering, with an optional minus sign prefix to indicate descending order.");
  }
}