<?php

namespace App\Infra\Factories\UseCases\Movie;
use App\Core\Application\UseCases\Movie\UpdateMovie\UpdateMovieUseCase;
use App\Core\Domain\Entities\User\User;
use App\Infra\Database\ConcreteRepositories\EloquentMovieRepository;
use App\Infra\Handlers\LaravelFileHandler;

class UpdateMovieUseCaseFactory {
  public static function make(User $loggedUser): UpdateMovieUseCase {
    $movieRepository = new EloquentMovieRepository();
    $fileManipulator = new LaravelFileHandler();

    return new UpdateMovieUseCase(
      $movieRepository,
      $fileManipulator,
      $loggedUser
    );
  }
}