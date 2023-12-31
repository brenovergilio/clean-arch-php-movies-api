<?php

namespace App\Core\Application\UseCases\Movie\FindMovie;
use App\Core\Application\UseCases\BaseUseCase;
use App\Core\Application\UseCases\Movie\FindMovie\DTO\FindMovieInputDTO;
use App\Core\Application\UseCases\Movie\FindMovie\DTO\FindMovieOutputDTO;
use App\Core\Domain\Entities\Movie\Movie;
use App\Core\Domain\Entities\Movie\MovieRepository;
use App\Core\Domain\Entities\User\User;
use App\Core\Domain\Exceptions\EntityNotFoundException;

class FindMovieUseCase extends BaseUseCase {
  public function __construct(
    private MovieRepository $movieRepository,
    private User $loggedUser
  ) {}

  public function execute(FindMovieInputDTO $input): FindMovieOutputDTO {
    $movie = $this->movieRepository->findByID($input->id);

    if(!$movie || (!$movie->isPublic() && !$this->loggedUser->isEmailConfirmed())) throw new EntityNotFoundException(Movie::CLASS_NAME);

    return $this->mapToOutput($movie);
  } 

  private function mapToOutput(Movie $movie): FindMovieOutputDTO {
    return new FindMovieOutputDTO(
      $movie->id(),
      $movie->title(),
      $movie->synopsis(),
      $movie->directorName(),
      $movie->genre(),
      $movie->cover(),
      $movie->isPublic(),
      $movie->releaseDate(),
      $movie->addedAt()
    );
  }
}