<?php
use App\Core\Application\UseCases\User\FindUser\FindUserUseCase;
use App\Infra\Database\ConcreteRepositories\EloquentUserRepository;

class FindUserUseCaseFactory {
  public static function make(): FindUserUseCase {
    $userRepository = new EloquentUserRepository();

    return new FindUserUseCase(
      $userRepository
    );
  }
}