<?php

namespace App\Core\Domain\Entities\Movie;

interface MovieRepository {
  public function create(Movie $movie, bool $returning = false): ?Movie;
  public function update(Movie $movie, bool $returning = false): ?Movie;
  public function findByID(string|int $id): ?Movie;
  public function findMany(): mixed;
  public function delete(string|int $id): void;
}