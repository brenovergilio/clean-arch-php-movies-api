<?php

namespace App\Presentation\Http\Controllers\Movie;
use App\Core\Application\UseCases\Movie\FindMovie\DTO\FindMovieInputDTO;
use App\Core\Application\UseCases\Movie\FindMovie\FindMovieUseCase;
use App\Core\Domain\Exceptions\EntityNotFoundException;
use App\Presentation\Http\HttpStatusCodes;
use App\Presentation\Http\HttpResponse;

class FindMovieController {
  public function __construct(private FindMovieUseCase $useCase) {}

  public function show(string|int $id): HttpResponse {
    try {
      
      $inputDto = new FindMovieInputDTO(
        $id
      );

      $result = $this->useCase->execute($inputDto);
      return new HttpResponse(["data" => $result], HttpStatusCodes::OK);
    } catch (EntityNotFoundException $exception) {
      return new HttpResponse(["error" => $exception->getMessage()], HttpStatusCodes::NOT_FOUND);
    }
  }
}