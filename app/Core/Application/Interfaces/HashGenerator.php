<?php

namespace App\Core\Application\Interfaces;

interface HashGenerator
{
  function generate(string $value): string;
}