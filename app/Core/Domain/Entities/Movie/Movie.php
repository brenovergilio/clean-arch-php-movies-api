<?php

namespace App\Core\Domain\Entities\Movie;
use DateTime;

class Movie {
  const CLASS_NAME = "Movie";

  public function __construct(
    private string|int|null $id,
    private string $title,
    private string $synopsis,
    private string $directorName,
    private MovieGenre $genre,
    private ?string $cover,
    private bool $isPublic,
    private DateTime $releaseDate,
    private DateTime $addedAt
  ) {}

  public function id(): string|int|null {
    return $this->id;
  }

  public function title(): string {
    return $this->title;
  }

  public function synopsis(): string {
    return $this->synopsis;
  }

  public function directorName(): string {
    return $this->directorName;
  }

  public function genre(): MovieGenre {
    return $this->genre;
  }

  public function cover(): ?string {
    return $this->cover;
  }

  public function isPublic(): bool {
    return $this->isPublic;
  }

  public function releaseDate(): DateTime {
    return $this->releaseDate;
  }

  public function addedAt(): DateTime {
    return $this->addedAt;
  }

  public function changeTitle(string $newTitle): void {
    $this->title = $newTitle;
  }

  public function changeSynopsis(string $newSynopsis): void {
    $this->synopsis = $newSynopsis;
  }

  public function changeDirectorName(string $newDirectorName): void {
    $this->directorName = $newDirectorName;
  }

  public function changeGenre(MovieGenre $newGenre): void {
    $this->genre = $newGenre;
  }

  public function changeCover(string $newCover): void {
    $this->cover = $newCover;
  }

  public function changeVisibility(bool $newVisibility): void {
    $this->isPublic = $newVisibility;
  }

  public function changeReleaseDate(DateTime $newReleaseDate): void {
    $this->releaseDate = $newReleaseDate;
  }
}