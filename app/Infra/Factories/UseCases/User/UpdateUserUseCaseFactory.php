<?php
use App\Core\Application\UseCases\User\UpdateUser\UpdateUserUseCase;
use App\Core\Domain\Entities\User\User;
use App\Infra\Database\ConcreteRepositories\EloquentAccessTokenRepository;
use App\Infra\Database\ConcreteRepositories\EloquentUserRepository;
use App\Infra\Mail\ConfirmAccountEmailSender;

class UpdateUserUseCaseFactory {
  public static function make(User $loggedUser): UpdateUserUseCase {
    $userRepository = new EloquentUserRepository();
    $accessTokenRepository = new EloquentAccessTokenRepository();
    $emailSender = new ConfirmAccountEmailSender();

    return new UpdateUserUseCase(
      $userRepository,
      $accessTokenRepository,
      $emailSender,
      $loggedUser
    );
  }
}