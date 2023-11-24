<?php
use App\Core\Application\UseCases\User\ConfirmEmail\ConfirmEmailUseCase;
use App\Infra\Factories\UseCases\User\ConfirmEmailUseCaseFactory;
use App\Models\UserModel;

it('should return an instance of ConfirmEmailUseCase', function() {
  $loggedUser = UserModel::factory()->makeOne()->mapToDomain();
  $useCase = ConfirmEmailUseCaseFactory::make($loggedUser);

  expect($useCase)->toBeInstanceOf(ConfirmEmailUseCase::class);
});