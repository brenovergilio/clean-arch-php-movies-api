<?php

namespace App\Core\Domain\Entities\User;
use App\Core\Domain\Exceptions\InvalidFieldException;

class CPF {

  public function __construct(
    private ?string $value
  ) {

    $this->value = $this->keepJustNumbers($value);
    $this->validateCPF();
  }

  function getValue(): ?string {
    return $this->value;
  }

  private function keepJustNumbers(?string $value): ?string {
    if(!$value) return null;
    return preg_replace('/[^0-9]/', '', $value);
  }

  private function validateCPF() {
    $fieldName = "CPF";

    if(!$this->value) return;
    if(strlen($this->value) !== 11) throw new InvalidFieldException($fieldName);
  }
}