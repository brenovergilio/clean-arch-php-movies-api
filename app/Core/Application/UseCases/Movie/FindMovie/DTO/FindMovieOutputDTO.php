<?php

namespace App\Core\Application\UseCases\Movie\FindMovie\DTO;
use App\Core\Domain\Entities\Movie\MovieGenre;
use DateTime;

class FindMovieOutputDTO {
  public function __construct(
    public string|int $id,
    public string $title,
    public string $synopsis,
    public string $directorName,
    public MovieGenre $genre,
    public ?string $cover,
    public bool $isPublic,
    public DateTime $releaseDate,
    public DateTime $addedAt
  ) {
     if(isset($cover)) {
      // Simulate a cloud URL
      $baseUrl = env("APP_URL");
      $this->cover = "$baseUrl/api/movies/$id/cover";
     }
  }
}