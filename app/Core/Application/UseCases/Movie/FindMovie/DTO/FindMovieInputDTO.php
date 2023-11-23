<?php

namespace App\Core\Application\UseCases\Movie\FindMovie\DTO;

class FindMovieInputDTO {
  public function __construct(
    public string|int $id
  ) {}
}