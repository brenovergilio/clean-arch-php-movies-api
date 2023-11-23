<?php

namespace App\Core\Application\UseCases\User\FindUser\DTO;

class FindUserInputDTO {
  public function __construct(
    public string|int $id
  ) {}
}