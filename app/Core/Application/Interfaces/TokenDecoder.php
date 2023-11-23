<?php

namespace App\Core\Application\Interfaces;

interface TokenDecoder {
  function decode(string $token);
}