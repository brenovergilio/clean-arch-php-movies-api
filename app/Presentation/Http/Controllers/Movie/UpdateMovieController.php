<?php

namespace App\Presentation\Http\Controllers\Movie;
use App\Core\Application\UseCases\Movie\UpdateMovie\DTO\UpdateMovieInputDTO;
use App\Core\Application\UseCases\Movie\UpdateMovie\UpdateMovieUseCase;
use App\Core\Domain\Exceptions\EntityNotFoundException;
use App\Core\Domain\Exceptions\MissingRequiredFieldException;
use App\Core\Application\Exceptions\InsufficientPermissionsException;
use App\Models\MovieModel;
use App\Presentation\Http\HttpStatusCodes;
use App\Presentation\Http\HttpRequest;
use App\Presentation\Http\HttpResponse;

class UpdateMovieController {
  public function __construct(private UpdateMovieUseCase $useCase) {}

  public function update(string|int $id, HttpRequest $request): HttpResponse {
    try {
      $inputDto = new UpdateMovieInputDTO(
        $id,
        $request->body['title'] ?? null,
        $request->body['synopsis'] ?? null,
        $request->body['directorName'] ?? null,
        MovieModel::mapGenreToDomain($request->body['genre'] ?? null),
        $request->body['cover'] ?? null,
        $request->body['isPublic'] ?? null,
        $request->body['releaseDate'] ?? null,
      );

      $this->useCase->execute($inputDto);
      return new HttpResponse(null, HttpStatusCodes::NO_CONTENT);
    } catch (MissingRequiredFieldException $exception) {
      return new HttpResponse(["error" => $exception->getMessage()], HttpStatusCodes::BAD_REQUEST);
    } catch (EntityNotFoundException $exception) {
      return new HttpResponse(["error" => $exception->getMessage()], HttpStatusCodes::NOT_FOUND);
    } catch (InsufficientPermissionsException $exception) {
      return new HttpResponse(["error" => $exception->getMessage()], HttpStatusCodes::FORBIDDEN);
    }
  }
}