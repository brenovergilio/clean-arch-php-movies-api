<?php

namespace App\Core\Application\Interface;

interface TokenGenerator
{
  function generate($target, $fieldsToTokenize, $expiration = null): string;
}