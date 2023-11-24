<?php

namespace App\Presentation\Http\Controllers\Movie;
use App\Core\Application\UseCases\Movie\CreateMovie\CreateMovieUseCase;
use App\Core\Application\UseCases\Movie\CreateMovie\DTO\CreateMovieInputDTO;
use App\Core\Domain\Exceptions\MissingRequiredFieldException;
use App\Models\MovieModel;
use App\Presentation\Http\Controllers\User\MovieControllerValidations;
use App\Presentation\Http\HttpStatusCodes;
use App\Presentation\Http\HttpRequest;
use App\Presentation\Http\HttpResponse;

class StoreMovieController {
  public function __construct(private CreateMovieUseCase $useCase) {}

  public function store(HttpRequest $request): HttpResponse {
    try {
      $validationException = MovieControllerValidations::createMovieValidations(array_keys($request->body))->validate($request->body);

      if($validationException) throw $validationException;

      $inputDto = new CreateMovieInputDTO(
        $request->body['title'],
        $request->body['synopsis'],
        $request->body['directorName'],
        MovieModel::mapGenreToDomain($request->body['genre']),
        $request->body['cover'] ?? null,
        $request->body['isPublic'],
        $request->body['releaseDate'],
      );

      $this->useCase->execute($inputDto);
      return new HttpResponse(null, HttpStatusCodes::NO_CONTENT);
    } catch (MissingRequiredFieldException $exception) {
      return new HttpResponse(["error" => $exception->getMessage()], HttpStatusCodes::BAD_REQUEST);
    }
  }
}