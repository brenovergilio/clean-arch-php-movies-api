<?php
use App\Core\Application\UseCases\Movie\DeleteMovie\DeleteMovieUseCase;
use App\Infra\Factories\UseCases\Movie\DeleteMovieUseCaseFactory;
use App\Models\UserModel;

it('should return an instance of DeleteMovieUseCase', function() {
  $loggedUser = UserModel::factory()->makeOne()->mapToDomain();
  $useCase = DeleteMovieUseCaseFactory::make($loggedUser);

  expect($useCase)->toBeInstanceOf(DeleteMovieUseCase::class);
});