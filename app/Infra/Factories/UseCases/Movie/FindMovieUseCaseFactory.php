<?php

namespace App\Infra\Factories\UseCases\Movie;
use App\Core\Application\UseCases\Movie\FindMovie\FindMovieUseCase;
use App\Infra\Database\ConcreteRepositories\EloquentMovieRepository;

class FindMovieUseCaseFactory {
  public static function make(): FindMovieUseCase {
    $movieRepository = new EloquentMovieRepository();

    return new FindMovieUseCase(
      $movieRepository
    );
  }
}