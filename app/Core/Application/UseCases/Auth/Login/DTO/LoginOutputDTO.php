<?php

namespace App\Core\Application\UseCases\Auth\Login\DTO;

class LoginOutputDTO {
  public function __construct(
    public string|int $id,
    public string $token,
    public bool $isAdmin
  ) {}
}