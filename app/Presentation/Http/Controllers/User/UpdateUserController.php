<?php

namespace App\Presentation\Http\Controllers\User;
use App\Core\Application\Exceptions\DuplicatedUniqueFieldException;
use App\Core\Application\Exceptions\InsufficientPermissionsException;
use App\Core\Application\UseCases\User\UpdateUser\DTO\UpdateUserInputDTO;
use App\Core\Application\UseCases\User\UpdateUser\UpdateUserUseCase;
use App\Core\Domain\Exceptions\EntityNotFoundException;
use App\Core\Domain\Exceptions\InvalidFieldException;
use App\Presentation\Http\HttpStatusCodes;
use App\Presentation\Http\HttpRequest;
use App\Presentation\Http\HttpResponse;

class UpdateUserController {
  public function __construct(private UpdateUserUseCase $useCase) {}
  
  public function update(string|int $id, HttpRequest $request): HttpResponse {
    try {
      $validationException = UserControllerValidations::updateUserValidations(array_keys($request->body))->validate($request->body);

      if($validationException) throw $validationException;

      $inputDto = new UpdateUserInputDTO(
        $id,
        $request->body['name'] ?? null,
        $request->body['email'] ?? null,
        $request->body['cpf'] ?? null,
        $request->body['photo'] ?? null
      );

      $this->useCase->execute($inputDto);
      return new HttpResponse(null, HttpStatusCodes::NO_CONTENT);
    } catch (EntityNotFoundException $exception) {
      return new HttpResponse(["error" => $exception->getMessage()], HttpStatusCodes::NOT_FOUND);
    } catch (InvalidFieldException $exception) {
      return new HttpResponse(["error" => $exception->getMessage()], HttpStatusCodes::BAD_REQUEST);
    } catch (DuplicatedUniqueFieldException $exception) {
      return new HttpResponse(["error" => $exception->getMessage()], HttpStatusCodes::CONFLICT);
    } catch (InsufficientPermissionsException $exception) {
      return new HttpResponse(["error" => $exception->getMessage()], HttpStatusCodes::FORBIDDEN);
    }
  }
}