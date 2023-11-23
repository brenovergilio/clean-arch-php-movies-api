<?php

namespace App\Core\Application\UseCases\Movie\DeleteMovie\DTO;

class DeleteMovieInputDTO {
  public function __construct(
    public string|int $id
  ) {}
}