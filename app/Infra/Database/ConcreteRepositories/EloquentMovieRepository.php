<?php

namespace App\Infra\Database\ConcreteRepositories;
use App\Core\Domain\Entities\Movie\Movie;
use App\Core\Domain\Entities\Movie\MovieRepository;
use App\Models\MovieModel;
use App\Core\Domain\Protocols\PaginatedResult;
use App\Core\Domain\Protocols\PaginationProps;
use App\Core\Domain\Entities\Movie\OrderMovies;
use App\Core\Domain\Entities\Movie\FilterMovies;

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

  public function findMany(FilterMovies $filterProps, OrderMovies $orderProps, PaginationProps $paginationProps): PaginatedResult {
    $moviesQuery = MovieModel::query();

    $moviesQuery->when($filterProps->fieldName, function($query, $field) {
      return 
        $query->where('title', 'like', "%$field%")
              ->orWhere('synopsis', 'like', "%$field%")
              ->orWhere('directorName', 'like', "%$field%")
              ->orWhere('genre', 'like', "%$field%");
    });

    if(isset($filterProps->isPublic)) {
      $moviesQuery->where('isPublic', $filterProps->isPublic);
    }

    foreach($orderProps->orderByProps as $orderProps) {
      $moviesQuery->orderBy($orderProps->fieldName, $orderProps->direction->value);
    }

    $eloquentPaginatedResult = $moviesQuery->paginate($paginationProps->perPage, ['*'], 'page', $paginationProps->page);

    $domainMappedMovies = array_map(function($eloquentMovie) {
      return $eloquentMovie->mapToDomain();
    }, $eloquentPaginatedResult->items());

    $paginationProps->total = $eloquentPaginatedResult->total();

    return new PaginatedResult($domainMappedMovies, $paginationProps);
  }
}