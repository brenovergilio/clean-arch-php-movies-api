<?php

namespace App\Presentation\Http\Controllers\Movie;
use App\Core\Application\UseCases\Movie\UpdateMovie\DTO\UpdateMovieInputDTO;
use App\Core\Application\UseCases\Movie\UpdateMovie\UpdateMovieUseCase;
use App\Core\Domain\Exceptions\EntityNotFoundException;
use App\Core\Application\Exceptions\InsufficientPermissionsException;
use App\Core\Domain\Exceptions\InvalidFieldException;
use App\Core\Domain\Helpers;
use App\Models\MovieModel;
use App\Presentation\Http\HttpStatusCodes;
use App\Presentation\Http\HttpRequest;
use App\Presentation\Http\HttpResponse;
use DateTime;

class UpdateMovieController {
  public function __construct(private UpdateMovieUseCase $useCase) {}

  public function update(string|int $id, HttpRequest $request): HttpResponse {
    try {
      $validationException = MovieControllerValidations::updateMovieValidations(array_keys($request->body))->validate($request->body);

      if($validationException) throw $validationException;

      $newGenre = isset($request->body['genre']) ? MovieModel::mapGenreToDomain($request->body['genre']) : null;
      $newReleaseDate = isset($request->body["releaseDate"]) ?  DateTime::createFromFormat("Y-m-d", $request->body["releaseDate"]) : null;
      $newVisibility = isset($request->body["isPublic"]) ? Helpers::convertStringBoolToPrimitive($request->body["isPublic"]) : null;

      if($newVisibility !== null && !is_bool($newVisibility)) {
        $newVisibility = $newVisibility === "true";
      }
      
      $inputDto = new UpdateMovieInputDTO(
        $id,
        $request->body['title'] ?? null,
        $request->body['synopsis'] ?? null,
        $request->body['directorName'] ?? null,
        $newGenre,
        $request->body['cover'] ?? null,
        $newVisibility,
        $newReleaseDate,
      );

      $this->useCase->execute($inputDto);
      return new HttpResponse(null, HttpStatusCodes::NO_CONTENT);
    } catch (EntityNotFoundException $exception) {
      return new HttpResponse(["error" => $exception->getMessage()], HttpStatusCodes::NOT_FOUND);
    } catch (InvalidFieldException $exception) {
      return new HttpResponse(["error" => $exception->getMessage()], HttpStatusCodes::NOT_FOUND);
    } catch (InsufficientPermissionsException $exception) {
      return new HttpResponse(["error" => $exception->getMessage()], HttpStatusCodes::FORBIDDEN);
    }
  }
}