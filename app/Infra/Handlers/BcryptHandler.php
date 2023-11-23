<?php

namespace App\Infra\Handlers;
use Illuminate\Support\Facades\Hash;
use App\Core\Application\Interfaces\HashComparer;
use App\Core\Application\Interfaces\HashGenerator;

class BcryptHandler implements HashComparer, HashGenerator
{
  public function compare(string $value, string $hashedValue): bool
  {
    return Hash::check($value, $hashedValue);
  }

  public function generate(string $value): string
  {
    return Hash::make($value);
  }
}