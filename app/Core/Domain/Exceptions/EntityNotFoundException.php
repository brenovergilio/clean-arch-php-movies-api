<?php

namespace App\Core\Domain\Exceptions;
use Exception;

class EntityNotFoundException extends Exception {
  public function __construct($entityName) {
    parent::__construct("Entity $entityName not found");
  }
}