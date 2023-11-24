<?php

namespace App\Presentation\Validations\Interfaces;
use Exception;

interface Validation {
  public function validate($input): ?Exception;
}