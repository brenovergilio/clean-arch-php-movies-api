<?php

namespace App\Core\Domain\Entities\Movie;
use App\Core\Domain\Protocols\PaginatedResult;
use App\Core\Domain\Protocols\OrderByProps;
use App\Core\Domain\Protocols\PaginationProps;

class FilterMovies {
  public function __construct(
    public ?string $fieldName
  ) {
    
    if($fieldName && !in_array($fieldName, ["title", "synopsis", "directorName", "genre", "isPublic"])) {
      $fieldName = null;
    }
  }
}

class OrderMovies {
  /**
  * @param OrderByProps[] $orderByProps
  */
  public function __construct(
    public array $orderByProps
  ) {
    foreach($orderByProps as $props) {
      if(!in_array($props, ["title", "genre", "isPublic", "releaseDate", "addedAt"])) {
        $props->fieldName = null;
      }
    }
  }
}

interface MovieRepository {
  public function create(Movie $movie, bool $returning = false): ?Movie;
  public function update(Movie $movie, bool $returning = false): ?Movie;
  public function findByID(string|int $id): ?Movie;
  public function findMany(FilterMovies $filterProps, OrderMovies $orderProps, PaginationProps $paginationProps): PaginatedResult;
  public function delete(string|int $id): void;
}