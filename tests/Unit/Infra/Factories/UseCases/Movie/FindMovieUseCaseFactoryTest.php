<?php
use App\Core\Application\UseCases\Movie\FindMovie\FindMovieUseCase;
use App\Infra\Factories\UseCases\Movie\FindMovieUseCaseFactory;

it('should return an instance of FindMovieUseCase', function() {
  $useCase = FindMovieUseCaseFactory::make();

  expect($useCase)->toBeInstanceOf(FindMovieUseCase::class);
});