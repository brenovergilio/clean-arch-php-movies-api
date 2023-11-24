<?php

namespace App\Core\Application\UseCases\Movie\CreateMovie;
use App\Core\Application\Interfaces\Folders;
use App\Core\Application\UseCases\BaseUseCase;
use App\Core\Application\UseCases\Movie\CreateMovie\DTO\CreateMovieInputDTO;
use App\Core\Domain\Entities\Movie\Movie;
use App\Core\Domain\Entities\Movie\MovieRepository;
use App\Core\Domain\Entities\User\User;
use DateTime;

class CreateMovieUseCase extends BaseUseCase {
  public function __construct(
    private MovieRepository $movieRepository,
    private User $loggedUser
  ) {}

  public function execute(CreateMovieInputDTO $input): void {
    $this->checkAdmin($this->loggedUser);

    $pathToCover = $input->cover?->upload(Folders::COVERS);

    $movie = new Movie(
      null,
      $input->title,
      $input->synopsis,
      $input->directorName,
      $input->genre,
      $pathToCover,
      $input->isPublic,
      $input->releaseDate,
      new DateTime()
    );

    $this->movieRepository->create($movie);
  }
}