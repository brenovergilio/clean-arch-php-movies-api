<?php

namespace App\Core\Application\Interfaces;

interface TokenGenerator
{
  function generate($target, $fieldsToTokenize, $expiration = null): string;
}