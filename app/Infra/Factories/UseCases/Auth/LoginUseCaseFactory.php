<?php

namespace App\Infra\Factories\UseCases\Auth;
use App\Core\Application\UseCases\Auth\Login\LoginUseCase;
use App\Infra\Database\ConcreteRepositories\EloquentUserRepository;
use App\Infra\Handlers\BcryptHandler;
use App\Infra\Handlers\JWTHandler;


class LoginUseCaseFactory {
  public static function make(): LoginUseCase {
    $userRepository = new EloquentUserRepository();
    $hashComparer = new BcryptHandler();
    $tokenGenerator = new JWTHandler();

    return new LoginUseCase(
      $userRepository,
      $hashComparer,
      $tokenGenerator
    );
  }
}