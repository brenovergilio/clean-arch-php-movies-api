<?php

namespace App\Core\Domain\Entities\User;
use App\Core\Domain\Entities\User\Role;

class User {

  private CPF $cpf;
  private Email $email;

  public function __construct(
    private string|int|null $id,
    private string $name,
    string $cpf,
    string $email,
    private string $password,
    private Role $role,
    private ?string $photo,
    private bool $emailConfirmed
  ) {
    if($cpf) $this->cpf = new CPF($cpf);
    $this->email = new Email($email);
  }

  public function id(): string|int|null {
    return $this->id;
  }

  public function name(): string {
    return $this->name;
  }

  public function photo(): ?string {
    return $this->photo;
  }

  public function email(): string {
    return $this->email->getValue();
  }

  public function password(): string {
    return $this->password;
  }

  public function cpf(): string {
    return $this->cpf->getValue();
  }

  public function changeName(string $name): void {
    $this->name = $name;
  }

  public function changePhoto(string $photo): void {
    $this->photo = $photo;
  }

  public function confirmEmail(): void {
    $this->emailConfirmed = true;
  }

  public function changeEmail(string $email): void {
    $this->email = new Email($email);
  }

  public function changePassword(string $password): void {
    $this->password = $password;
  }

  public function changeCPF(string $cpf): void {
    $this->cpf = new CPF($cpf);
  }

  public function isEmailConfirmed(): bool {
    return $this->emailConfirmed;
  }

  public function isAdmin(): bool {
    return $this->role == Role::ADMIN;
  }
}