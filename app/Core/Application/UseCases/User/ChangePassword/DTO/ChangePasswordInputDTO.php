<?php

namespace App\Core\Application\UseCases\User\ChangePassword\DTO;

class ChangePasswordInputDTO {
  public function __construct(
    public string $newPassword,
    public string $newPasswordConfirmation,
    public string $oldPassword
  ) {}
}