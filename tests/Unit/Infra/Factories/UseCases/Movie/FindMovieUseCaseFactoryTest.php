<?php
use App\Core\Application\UseCases\Movie\FindMovie\FindMovieUseCase;
use App\Infra\Factories\UseCases\Movie\FindMovieUseCaseFactory;
use App\Models\UserModel;

it('should return an instance of FindMovieUseCase', function() {
  $loggedUser = UserModel::factory()->makeOne()->mapToDomain();
  $useCase = FindMovieUseCaseFactory::make($loggedUser);

  expect($useCase)->toBeInstanceOf(FindMovieUseCase::class);
});