<?php

namespace App\Infra\Factories\UseCases\User;
use App\Core\Application\UseCases\User\ConfirmEmail\ConfirmEmailUseCase;
use App\Core\Domain\Entities\User\User;
use App\Infra\Database\ConcreteRepositories\EloquentAccessTokenRepository;
use App\Infra\Database\ConcreteRepositories\EloquentUserRepository;

class ConfirmEmailUseCaseFactory {
  public static function make(User $loggedUser): ConfirmEmailUseCase {
    $userRepository = new EloquentUserRepository();
    $accessTokenRepository = new EloquentAccessTokenRepository();

    return new ConfirmEmailUseCase(
      $userRepository,
      $accessTokenRepository
    );
  }
}