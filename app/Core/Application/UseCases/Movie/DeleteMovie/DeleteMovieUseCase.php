<?php

namespace App\Core\Application\UseCases\Movie\DeleteMovie;
use App\Core\Application\Interfaces\FileManipulator;
use App\Core\Application\UseCases\BaseUseCase;
use App\Core\Application\UseCases\Movie\DeleteMovie\DTO\DeleteMovieInputDTO;
use App\Core\Domain\Entities\Movie\Movie;
use App\Core\Domain\Entities\Movie\MovieRepository;
use App\Core\Domain\Exceptions\EntityNotFoundException;
use App\Core\Domain\Entities\User\User;

class DeleteMovieUseCase extends BaseUseCase {
  public function __construct(
    private MovieRepository $movieRepository,
    private FileManipulator $fileManipulator,
    private User $loggedUser
  ) {}

  public function execute(DeleteMovieInputDTO $input): void {
    $this->checkAdmin($this->loggedUser);

    $movie = $this->movieRepository->findByID($input->id);

    if(!$movie) throw new EntityNotFoundException(Movie::CLASS_NAME);

    $this->movieRepository->delete($input->id);
    $this->deleteMovieCover($movie->cover());
  }

  private function deleteMovieCover(?string $cover): void {
    if(!$cover) return;
    if(!$this->fileManipulator->exists($cover)) return;
    
    $this->fileManipulator->delete($cover);
  }
}