<?php

namespace App\Presentation\Http\Controllers\User;
use App\Core\Application\UseCases\User\ConfirmEmail\ConfirmEmailUseCase;
use App\Core\Application\UseCases\User\ConfirmEmail\DTO\ConfirmEmailInputDTO;
use App\Core\Domain\Exceptions\EntityNotFoundException;
use App\Core\Domain\Exceptions\InvalidFieldException;
use App\Core\Domain\Exceptions\MissingRequiredFieldException;
use App\Presentation\Http\HttpStatusCodes;
use App\Presentation\Http\HttpRequest;
use App\Presentation\Http\HttpResponse;

class ConfirmEmailUserController {
  public function __construct(private ConfirmEmailUseCase $useCase) {}

  public function confirmEmail(HttpRequest $request): HttpResponse {
    try {
      $validationException = UserControllerValidations::confirmEmailValidations()->validate($request->body);

      if($validationException) throw $validationException;

      $inputDto = new ConfirmEmailInputDTO(
        $request->body['accessToken']
      );

      $this->useCase->execute($inputDto);
      return new HttpResponse(null, HttpStatusCodes::NO_CONTENT);
    } catch (MissingRequiredFieldException $exception) {
      return new HttpResponse(["error" => $exception->getMessage()], HttpStatusCodes::BAD_REQUEST);
    } catch (InvalidFieldException $exception) {
      return new HttpResponse(["error" => $exception->getMessage()], HttpStatusCodes::BAD_REQUEST);
    } catch (EntityNotFoundException $exception) {
      return new HttpResponse(["error" => $exception->getMessage()], HttpStatusCodes::NOT_FOUND);
    }
  }
}