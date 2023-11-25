<?php

namespace App\Infra\Database\ConcreteRepositories;
use App\Core\Domain\Entities\Movie\Movie;
use App\Core\Domain\Entities\Movie\MovieRepository;
use App\Models\MovieModel;

class EloquentMovieRepository implements MovieRepository {
  public function create(Movie $movie, bool $returning = false): ?Movie {
    $eloquentMovie = new MovieModel;
    $eloquentMovie->mergeDomain($movie);
    $eloquentMovie->save();

    if($returning) {
      $eloquentMovie->refresh();
      return $eloquentMovie->mapToDomain();
    }

    return null;
  }

  public function update(Movie $movie, bool $returning = false): ?Movie {
    $eloquentMovie = MovieModel::find($movie->id());
    $eloquentMovie->mergeDomain($movie);
    $eloquentMovie->save();

    if($returning) {
      $eloquentMovie->refresh();
      return $eloquentMovie->mapToDomain();
    }

    return null;
  }

  public function findByID(string|int $id): ?Movie {
    $eloquentMovie = MovieModel::find($id);

    if(!$eloquentMovie) return null;

    return $eloquentMovie->mapToDomain();
  }

  public function delete(string|int $id): void {
    MovieModel::destroy($id);
  }

  public function findMany(): mixed {
    return MovieModel::all();
  }
}