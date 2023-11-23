<?php

namespace App\Core\Application\UseCases\Movie\FindMovie;

class FindMovieInputDTO {
  public function __construct(
    public string|int $id
  ) {}
}