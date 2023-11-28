<?php

namespace App\Core\Domain\Entities\Movie;
use App\Core\Domain\Protocols\PaginatedResult;
use App\Core\Domain\Protocols\OrderByProps;
use App\Core\Domain\Protocols\PaginationProps;

class FilterMovies {
  public function __construct(
    public ?string $fieldValue,
    public ?bool $isPublic
  ) {}
}

class OrderMovies {
  /**
  * @param OrderByProps[] $orderByProps
  */
  public function __construct(
    public array $orderByProps
  ) {
    foreach($orderByProps as $props) {
      if(!in_array($props->fieldName, ["title", "isPublic", "releaseDate", "addedAt"])) {
        $props->fieldName = null;
      }
    }
  }
}

interface MovieRepository {
  public function create(Movie $movie, bool $returning = false): ?Movie;
  public function update(Movie $movie, bool $returning = false): ?Movie;
  public function findByID(string|int $id): ?Movie;
  public function findMany(PaginationProps $paginationProps, ?FilterMovies $filterProps, ?OrderMovies $orderProps): PaginatedResult;
  public function delete(string|int $id): void;
}