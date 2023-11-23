<?php

namespace App\Core\Application\UseCases\Movie\UpdateMovie\DTO;
use App\Core\Application\Interfaces\UploadableFile;
use App\Core\Domain\Entities\Movie\MovieGenre;
use DateTime;

class UpdateMovieInputDTO {
  public function __construct(
    public string|int $id,
    public ?string $title,
    public ?string $synopsis,
    public ?string $directorName,
    public ?MovieGenre $genre,
    public ?UploadableFile $cover,
    public ?bool $isPublic,
    public ?DateTime $releaseDate
  ) {}
}