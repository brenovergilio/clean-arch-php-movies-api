<?php

namespace App\Infra\Factories\UseCases\Movie;
use App\Core\Application\UseCases\Movie\FindManyMovies\FindManyMoviesUseCase;
use App\Core\Domain\Entities\User\User;
use App\Infra\Database\ConcreteRepositories\EloquentMovieRepository;

class FindManyMoviesUseCaseFactory {
  public static function make(User $loggedUser): FindManyMoviesUseCase {
    $movieRepository = new EloquentMovieRepository();

    return new FindManyMoviesUseCase(
      $movieRepository,
      $loggedUser
    );
  }
}