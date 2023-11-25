<?php

namespace App\Infra\Factories\UseCases\Movie;
use App\Core\Application\UseCases\Movie\DeleteMovie\DeleteMovieUseCase;
use App\Core\Domain\Entities\User\User;
use App\Infra\Database\ConcreteRepositories\EloquentMovieRepository;
use App\Infra\Handlers\LaravelFileHandler;

class DeleteMovieUseCaseFactory {
  public static function make(User $loggedUser): DeleteMovieUseCase {
    $movieRepository = new EloquentMovieRepository();
    $fileManipulator = new LaravelFileHandler();

    return new DeleteMovieUseCase(
      $movieRepository,
      $fileManipulator,
      $loggedUser
    );
  }
}