<?php
use App\Core\Application\UseCases\Movie\CreateMovie\CreateMovieUseCase;
use App\Infra\Factories\UseCases\Movie\CreateMovieUseCaseFactory;
use App\Models\UserModel;

it('should return an instance of CreateMovieUseCase', function() {
  $loggedUser = UserModel::factory()->makeOne()->mapToDomain();
  $useCase = CreateMovieUseCaseFactory::make($loggedUser);

  expect($useCase)->toBeInstanceOf(CreateMovieUseCase::class);
});