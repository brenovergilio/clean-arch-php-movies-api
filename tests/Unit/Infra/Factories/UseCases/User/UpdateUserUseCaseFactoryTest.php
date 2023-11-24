<?php
use App\Core\Application\UseCases\User\UpdateUser\UpdateUserUseCase;
use App\Infra\Factories\UseCases\User\UpdateUserUseCaseFactory;
use App\Models\UserModel;

it('should return an instance of UpdateUserUseCase', function() {
  $loggedUser = UserModel::factory()->makeOne()->mapToDomain();
  $useCase = UpdateUserUseCaseFactory::make($loggedUser);

  expect($useCase)->toBeInstanceOf(UpdateUserUseCase::class);
});