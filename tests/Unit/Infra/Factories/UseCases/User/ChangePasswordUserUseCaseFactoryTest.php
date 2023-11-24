<?php
use App\Core\Application\UseCases\User\ChangePassword\ChangePasswordUseCase;
use App\Infra\Factories\UseCases\User\ChangePasswordUseCaseFactory;
use App\Models\UserModel;

it('should return an instance of ChangePasswordUseCase', function() {
  $loggedUser = UserModel::factory()->makeOne()->mapToDomain();
  $useCase = ChangePasswordUseCaseFactory::make($loggedUser);

  expect($useCase)->toBeInstanceOf(ChangePasswordUseCase::class);
});