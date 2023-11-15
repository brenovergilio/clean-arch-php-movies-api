<?php

namespace App\Core\Domain\Entities\User;
use App\Core\Domain\Entities\Role;

class User {

  private CPF $cpf;
  private Email $email;

  public function __construct(
    private string|int $id,
    private string $name,
    string $cpf,
    string $email,
    private Role $role,
    private string $photo,
    private bool $emailConfirmed
  ) {
    $this->cpf = new CPF($cpf);
    $this->email = new Email($email);
  }

  public function id(): string|int {
    return $this->id;
  }

  public function email(): string {
    return $this->email->getValue();
  }

  public function cpf(): string {
    return $this->cpf->getValue();
  }

  public function isEmailConfirmed(): bool {
    return $this->emailConfirmed;
  }

  public function isAdmin(): bool {
    return $this->role == Role::ADMIN;
  }
}