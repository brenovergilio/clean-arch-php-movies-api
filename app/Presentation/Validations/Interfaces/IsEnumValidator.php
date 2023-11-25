<?php

namespace App\Presentation\Validations\Interfaces;

interface IsEnumValidator {
  public function isValid(string $enum, string $enumName): bool;
}