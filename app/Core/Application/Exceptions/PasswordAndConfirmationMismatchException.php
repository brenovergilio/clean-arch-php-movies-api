<?php

namespace App\Core\Application\Exceptions;
use Exception;

class PasswordAndConfirmationMismatchException extends Exception
{
  public function __construct() {
    parent::__construct("Password and confirmation are not equal");
}
}