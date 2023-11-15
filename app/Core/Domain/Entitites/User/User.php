<?php

namespace App\Core\Domain\Entities\User;

class User {
  public function __construct(
    private string|int $id,
    private string $name,
    private string $photo,
    private bool $emailConfirmed
  ) {}

  public function isEmailConfirmed(): bool {
    return $this->emailConfirmed;
  }
}