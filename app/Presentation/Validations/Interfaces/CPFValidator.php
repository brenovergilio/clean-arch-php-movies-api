<?php

namespace App\Presentation\Validations\Interfaces;

interface CPFValidator {
  public function isValid(string $cpf): bool;
}