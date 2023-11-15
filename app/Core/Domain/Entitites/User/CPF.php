<?php

namespace App\Core\Domain\Entities\User;
use App\Core\Domain\Exceptions\InvalidFieldException;

class CPF {
  public function __construct(
    private string $value
  ) {
    $this->value = $this->keepJustNumbers($value);
    $this->validateCPF();
  }

  private function keepJustNumbers(string $value): string {
    return preg_replace('/[^0-9]/', '', $value);
  }

  private function validateCPF() {
    $fieldName = "CPF";

    if(strlen($this->value !== 11)) throw new InvalidFieldException($fieldName);

    if(preg_match('/^(\d)\1+$/', $this->value)) throw new InvalidFieldException($fieldName);

    for ($i = 9, $j = 10, $sum = 0; $i > 0; $i--, $j--) {
      $sum += $this->value[$i - 1] * $j;
    }

    $rest = $sum % 11;
    $firstVerifyingDigit = ($rest < 2) ? 0 : 11 - $rest;

    if ($this->value[9] != $firstVerifyingDigit) throw new InvalidFieldException($fieldName);

    for ($i = 10, $j = 11, $sum = 0; $i > 0; $i--, $j--) {
        $sum += $this->value[$i - 1] * $j;
    }

    $rest = $sum % 11;
    $secondVerifyingDigit = ($rest < 2) ? 0 : 11 - $rest;

    if ($this->value[10] != $secondVerifyingDigit) throw new InvalidFieldException($fieldName);
  }
}