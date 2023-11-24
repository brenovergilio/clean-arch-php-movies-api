<?php
use App\Core\Application\UseCases\User\CreateUser\CreateUserUseCase;
use App\Infra\Database\ConcreteRepositories\EloquentAccessTokenRepository;
use App\Infra\Database\ConcreteRepositories\EloquentUserRepository;
use App\Infra\Handlers\BcryptHandler;
use App\Infra\Mail\ConfirmAccountEmailSender;

class CreateUserUseCaseFactory {
  public static function make(): CreateUserUseCase {
    $userRepository = new EloquentUserRepository();
    $accessTokenRepository = new EloquentAccessTokenRepository();
    $hashGenerator = new BcryptHandler();
    $emailSender = new ConfirmAccountEmailSender();

    return new CreateUserUseCase(
      $userRepository,
      $accessTokenRepository,
      $hashGenerator,
      $emailSender
    );
  }
}