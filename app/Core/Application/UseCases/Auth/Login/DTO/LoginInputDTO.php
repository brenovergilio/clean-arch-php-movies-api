<?php

namespace App\Core\Application\UseCases\Auth\Login\DTO;

class LoginInputDTO {
  public function __construct(
    public string $email,
    public string $password
  ) {}
}