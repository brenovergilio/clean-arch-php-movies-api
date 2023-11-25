<?php

namespace App\Presentation\Validations\Interfaces;

interface DateTimeValidator {
  public function isValid(string $date): bool;
}