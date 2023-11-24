<?php

namespace App\Presentation\Interfaces;
use Exception;

interface Validation {
  public function validate($input): ?Exception;
}