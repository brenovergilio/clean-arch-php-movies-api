<?php

namespace App\Core\Domain\Exceptions;
use Exception;

class WeakPasswordException extends Exception {
  public function __construct() {
    parent::__construct("The password must contain at least one uppercase letter, one lowercase letter, one special character, and one number");
  }
}