<?php

namespace App\Core\Application\UseCases\Movie\FindManyMovies;
use App\Core\Application\UseCases\Movie\FindManyMovies\DTO\FindManyMoviesInputDTO;
use App\Core\Application\UseCases\Movie\FindManyMovies\DTO\FindManyMoviesOutputDTO;
use App\Core\Domain\Entities\Movie\MovieRepository;
use App\Core\Domain\Entities\User\User;

class FindManyMoviesUseCase {
  public function __construct(
    private MovieRepository $movieRepository,
    private User $loggedUser
  ) {}

  public function execute(FindManyMoviesInputDTO $input): FindManyMoviesOutputDTO {
    if(!$this->loggedUser->isEmailConfirmed()) $input->filterMoviesProps->isPublic = true;

    $paginatedMovies = $this->movieRepository->findMany($input->paginationProps, $input->filterMoviesProps, $input->orderMoviesProps);
    
    $outputMappedMovies = array_map(function($domainMovie) {
      $returnObj = new \stdClass;
      $returnObj->id = $domainMovie->id();
      $returnObj->title = $domainMovie->title();
      $returnObj->synopsis = $domainMovie->synopsis();
      $returnObj->directorName = $domainMovie->directorName();
      $returnObj->genre = $domainMovie->genre();
      $cover = $domainMovie->cover();

      if($cover) {
        // Simulate a cloud URL 
        $appUrl = env("APP_URL");
        $returnObj->cover = "$appUrl/api/movie/$returnObj->id/cover";
      }

      $returnObj->isPublic = $domainMovie->isPublic();
      $returnObj->releaseDate = $domainMovie->releaseDate();
      $returnObj->addedAt = $domainMovie->addedAt();

      return $returnObj;
    }, $paginatedMovies->data);

    return new FindManyMoviesOutputDTO($outputMappedMovies, $paginatedMovies->paginationProps);
  }
}