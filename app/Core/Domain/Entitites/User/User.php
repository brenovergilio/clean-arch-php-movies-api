<?php

namespace App\Core\Domain\Entities\User;

class User {

  public function __construct(
    private string|int $id,
    private string $name,
    string $cpf,
    string $email,
    private string $photo,
    private bool $emailConfirmed
  ) {
    $this->cpf = new CPF($cpf);
    $this->email = new Email($email);
  }

  public function isEmailConfirmed(): bool {
    return $this->emailConfirmed;
  }
}