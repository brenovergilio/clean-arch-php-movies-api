<?php
use App\Core\Application\UseCases\Auth\Login\LoginUseCase;
use App\Infra\Factories\UseCases\Auth\LoginUseCaseFactory;

it('should return an instance of LoginUseCase', function() {
  $useCase = LoginUseCaseFactory::make();
  
  expect($useCase)->toBeInstanceOf(LoginUseCase::class);
});