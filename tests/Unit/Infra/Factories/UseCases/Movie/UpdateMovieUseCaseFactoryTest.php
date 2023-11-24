<?php
use App\Core\Application\UseCases\Movie\UpdateMovie\UpdateMovieUseCase;
use App\Infra\Factories\UseCases\Movie\UpdateMovieUseCaseFactory;
use App\Models\UserModel;

it('should return an instance of UpdateMovieUseCase', function() {
  $loggedUser = UserModel::factory()->makeOne()->mapToDomain();
  $useCase = UpdateMovieUseCaseFactory::make($loggedUser);

  expect($useCase)->toBeInstanceOf(UpdateMovieUseCase::class);
});