<?php
use App\Core\Application\UseCases\User\CreateUser\CreateUserUseCase;
use App\Infra\Factories\UseCases\User\CreateUserUseCaseFactory;

it('should return an instance of CreateUserUseCase', function() {
  $useCase = CreateUserUseCaseFactory::make();

  expect($useCase)->toBeInstanceOf(CreateUserUseCase::class);
});