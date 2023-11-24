<?php
use App\Core\Application\UseCases\User\FindUser\FindUserUseCase;
use App\Infra\Factories\UseCases\User\FindUserUseCaseFactory;

it('should return an instance of FindUserUseCase', function() {
  $useCase = FindUserUseCaseFactory::make();

  expect($useCase)->toBeInstanceOf(FindUserUseCase::class);
});