<?php
use App\Core\Application\UseCases\Movie\FindManyMovies\FindManyMoviesUseCase;
use App\Infra\Factories\UseCases\Movie\FindManyMoviesUseCaseFactory;
use App\Models\UserModel;

it('should return an instance of FindManyMoviesUseCase', function() {
  $loggedUser = UserModel::factory()->makeOne()->mapToDomain();
  $useCase = FindManyMoviesUseCaseFactory::make($loggedUser);

  expect($useCase)->toBeInstanceOf(FindManyMoviesUseCase::class);
});