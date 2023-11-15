<?php

namespace App\Core\Application\UseCases\User\CreateUser\DTO;
use App\Core\Application\Interfaces\UploadableFile;

class CreateUserInputDTO {
  public function __construct(
    public string $name,
    public string $email,
    public string $cpf,
    public string $password,
    public string $passwordConfirmation,
    public ?UploadableFile $photo
  ) {}
}