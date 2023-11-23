<?php

namespace App\Core\Application\UseCases\User\FindUser\DTO;

class FindUserOutputDTO {
  public function __construct(
    public string|int $id,
    public string $name,
    public ?string $cpf,
    public string $email,
    public ?string $photo,
    public bool $isAdmin,
    public bool $emailConfirmed
  ) {}
}