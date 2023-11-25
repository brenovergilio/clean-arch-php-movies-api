<?php

namespace App\Core\Application\UseCases\User\UpdateUser\DTO;
use App\Core\Application\Interfaces\UploadableFile;

class UpdateUserInputDTO {
  public function __construct(
    public ?string $name,
    public ?string $email,
    public ?string $cpf,
    public ?UploadableFile $photo
  ) {}
}