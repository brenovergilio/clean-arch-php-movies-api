<?php

namespace App\Core\Application\Exceptions;
use Exception;

class InsufficientPermissionsException extends Exception
{
  public function __construct() {
    parent::__construct("Insufficient Permissions");
}
}