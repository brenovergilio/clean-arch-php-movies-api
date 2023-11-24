<?php

namespace App\Core\Application\Interfaces;

interface TokenGenerator
{
  function generate($target, array $fieldsToTokenize, ?int $expiration = null, bool $usingGetter = true): string;
}