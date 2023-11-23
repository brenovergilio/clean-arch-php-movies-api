<?php

namespace App\Core\Application\UseCases\User\ConfirmEmail\DTO;

class ConfirmEmailInputDTO {
  public function __construct(
    public string $accessTokenValue
  ) {}
}