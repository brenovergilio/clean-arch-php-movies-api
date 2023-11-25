<?php

namespace App\Core\Application\UseCases\Movie\UpdateMovie;
use App\Core\Application\Interfaces\FileManipulator;
use App\Core\Application\Interfaces\Folders;
use App\Core\Application\UseCases\BaseUseCase;
use App\Core\Application\UseCases\Movie\UpdateMovie\DTO\UpdateMovieInputDTO;
use App\Core\Domain\Entities\Movie\Movie;
use App\Core\Domain\Entities\Movie\MovieRepository;
use App\Core\Domain\Entities\User\User;
use App\Core\Domain\Exceptions\EntityNotFoundException;

class UpdateMovieUseCase extends BaseUseCase {
  public function __construct(
    private MovieRepository $movieRepository,
    private FileManipulator $fileManipulator,
    private User $loggedUser
  ) {}

  public function execute(UpdateMovieInputDTO $input): void {
    $this->checkAdmin($this->loggedUser);

    $movie = $this->movieRepository->findByID($input->id);
    if(!$movie) throw new EntityNotFoundException(Movie::CLASS_NAME);

    $this->mergeProperties($input, $movie);
    $this->movieRepository->update($movie);
  }

  private function mergeProperties(UpdateMovieInputDTO $input, Movie $movie): void {
    if($input->title) $movie->changeTitle($input->title);

    if($input->synopsis) $movie->changeSynopsis($input->synopsis);

    if($input->directorName) $movie->changeDirectorName($input->directorName);

    if($input->genre) $movie->changeGenre($input->genre);

    if($input->cover) {
      $oldCoverExists = $movie->cover() && $this->fileManipulator->exists($movie->cover());

      if($oldCoverExists) $this->fileManipulator->delete($movie->cover());

      $movie->changeCover($input->cover->upload(Folders::COVERS));
    }

    if($input->isPublic !== null) $movie->changeVisibility($input->isPublic);

    if($input->releaseDate) $movie->changeReleaseDate($input->releaseDate);
  }
}