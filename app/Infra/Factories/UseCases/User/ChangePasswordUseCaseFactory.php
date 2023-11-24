<?php

namespace App\Infra\Factories\UseCases\User;
use App\Core\Application\UseCases\User\ChangePassword\ChangePasswordUseCase;
use App\Core\Domain\Entities\User\User;
use App\Infra\Database\ConcreteRepositories\EloquentUserRepository;
use App\Infra\Handlers\BcryptHandler;

class ChangePasswordUseCaseFactory {
  public static function make(User $loggedUser): ChangePasswordUseCase {
    $userRepository = new EloquentUserRepository();
    $bcryptHandler = new BcryptHandler();

    return new ChangePasswordUseCase(
      $userRepository,
      $bcryptHandler,
      $bcryptHandler,
      $loggedUser
    );
  }
}