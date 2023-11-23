<?php

namespace App\Core\Domain\Exceptions;
use Exception;

class ExpiredAccessTokenException extends Exception {
  public function __construct() {
    parent::__construct("Access Token is expired");
  }
}