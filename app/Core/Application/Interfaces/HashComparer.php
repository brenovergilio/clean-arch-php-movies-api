<?php

namespace App\Core\Application\Interfaces;

interface HashComparer
{
  function compare(string $hashedValue, string $originalValue): bool;
}