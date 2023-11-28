<?php

namespace App\Core\Domain\Entities\User;
use App\Core\Domain\Exceptions\InvalidFieldException;
use App\Core\Domain\Exceptions\MissingRequiredFieldException;

class CPF {
  const CLASS_NAME = "CPF";
  public function __construct(
    private ?string $value
  ) {

    $this->value = $this->keepJustNumbers($value);
    $this->validateCPF();
  }

  function getValue(): string {
    return $this->value;
  }

  private function keepJustNumbers(?string $value): ?string {
    if(!$value) return null;
    return preg_replace('/[^0-9]/', '', $value);
  }

  private function validateCPF() {
    if(!$this->value) throw new MissingRequiredFieldException(CPF::CLASS_NAME);

    if(strlen($this->value) !== 11) throw new InvalidFieldException(CPF::CLASS_NAME);

    if (strlen($this->value) != 11 || preg_match("/^{$this->value[0]}{11}$/", $this->value)) {
       throw new InvalidFieldException(CPF::CLASS_NAME);
    }

    for ($s = 10, $n = 0, $i = 0; $s >= 2; $n += $this->value[$i++] * $s--);

    if ($this->value[9] != ((($n %= 11) < 2) ? 0 : 11 - $n)) {
         throw new InvalidFieldException(CPF::CLASS_NAME);
    }

    for ($s = 11, $n = 0, $i = 0; $s >= 2; $n += $this->value[$i++] * $s--);

    if ($this->value[10] != ((($n %= 11) < 2) ? 0 : 11 - $n)) {
         throw new InvalidFieldException(CPF::CLASS_NAME);
    }
  }
}