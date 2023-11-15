<?php

namespace App\Core\Domain\Entities\User;

class User {

  public function __construct(
    private string|int $id,
    private string $name,
    string $cpf,
    private string $photo,
    private bool $emailConfirmed
  ) {
    $this->cpf = new CPF($cpf);
  }

  public function isEmailConfirmed(): bool {
    return $this->emailConfirmed;
  }
}