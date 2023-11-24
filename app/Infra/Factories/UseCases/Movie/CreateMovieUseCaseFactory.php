<?php

namespace App\Infra\Factories\UseCases\Movie;
use App\Core\Domain\Entities\User\User;
use App\Core\Application\UseCases\Movie\CreateMovie\CreateMovieUseCase;
use App\Infra\Database\ConcreteRepositories\EloquentMovieRepository;

class CreateMovieUseCaseFactory {
  public static function make(User $loggedUser): CreateMovieUseCase {
    $movieRepository = new EloquentMovieRepository();

    return new CreateMovieUseCase(
      $movieRepository,
      $loggedUser
    );
  }
}