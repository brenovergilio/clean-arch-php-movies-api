<?php

namespace App\Infra\Factories\UseCases\User;
use App\Core\Application\UseCases\User\UpdateUser\UpdateUserUseCase;
use App\Core\Domain\Entities\User\User;
use App\Infra\Database\ConcreteRepositories\EloquentAccessTokenRepository;
use App\Infra\Database\ConcreteRepositories\EloquentUserRepository;
use App\Infra\Handlers\LaravelFileHandler;
use App\Infra\Mail\ConfirmAccountEmailSender;

class UpdateUserUseCaseFactory {
  public static function make(User $loggedUser): UpdateUserUseCase {
    $userRepository = new EloquentUserRepository();
    $accessTokenRepository = new EloquentAccessTokenRepository();
    $emailSender = new ConfirmAccountEmailSender();
    $fileManipulador = new LaravelFileHandler();

    return new UpdateUserUseCase(
      $userRepository,
      $accessTokenRepository,
      $emailSender,
      $fileManipulador,
      $loggedUser
    );
  }
}