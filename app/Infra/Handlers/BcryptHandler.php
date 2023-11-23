<?php

namespace App\Infra\Handlers;
use App\Core\Application\Interfaces\HashComparer;
use App\Core\Application\Interfaces\HashGenerator;

class BcryptHandler implements HashComparer, HashGenerator
{
  public function compare(string $value, string $hashedValue): bool
  {
    return password_verify($value, $hashedValue);
  }

  public function generate(string $value): string
  {
    return password_hash($value, PASSWORD_BCRYPT);
  }
}