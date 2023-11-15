<?php

namespace App\Core\Application\UseCases\User\CreateUser;

use App\Core\Application\UseCases\BaseUseCase;
use App\Core\Application\UseCases\User\CreateUser\DTO\CreateUserInputDTO;
use App\Core\Domain\Entities\User\UserRepository;

class CreateUserUseCase extends BaseUseCase {
  public function __construct(
    private UserRepository $userRepository
  ) {}

  public function execute(CreateUserInputDTO $input): void {

  }
}