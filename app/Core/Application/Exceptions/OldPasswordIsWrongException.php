<?php

namespace App\Core\Application\Exceptions;
use Exception;

class OldPasswordIsWrongException extends Exception
{
  public function __construct() {
    parent::__construct("Old password is wrong");
}
}