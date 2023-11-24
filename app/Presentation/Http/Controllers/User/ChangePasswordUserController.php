<?php

namespace App\Presentation\Http\Controllers\User;
use App\Core\Application\Exceptions\OldPasswordIsWrongException;
use App\Core\Application\Exceptions\PasswordAndConfirmationMismatchException;
use App\Core\Application\UseCases\User\ChangePassword\ChangePasswordUseCase;
use App\Core\Application\UseCases\User\ChangePassword\DTO\ChangePasswordInputDTO;
use App\Core\Domain\Exceptions\MissingRequiredFieldException;
use App\Core\Domain\Exceptions\WeakPasswordException;
use App\Presentation\Http\HttpStatusCodes;
use App\Presentation\Http\HttpRequest;
use App\Presentation\Http\HttpResponse;

class ChangePasswordUserController {
  public function __construct(private ChangePasswordUseCase $useCase) {}
 
  public function changePassword(HttpRequest $request): HttpResponse {
    try {
      $validationException = UserControllerValidations::changePasswordValidations()->validate($request->body);

      if($validationException) throw $validationException;

      $inputDto = new ChangePasswordInputDTO(
        $request->body['newPassword'],
        $request->body['newPasswordConfirmation'],
        $request->body['oldPassword'],
      );

      $this->useCase->execute($inputDto);
      return new HttpResponse(null, HttpStatusCodes::NO_CONTENT);
    } catch (MissingRequiredFieldException $exception) {
      return new HttpResponse(["error" => $exception->getMessage()], HttpStatusCodes::BAD_REQUEST);
    } catch (PasswordAndConfirmationMismatchException $exception) {
      return new HttpResponse(["error" => $exception->getMessage()], HttpStatusCodes::BAD_REQUEST);
    } catch (WeakPasswordException $exception) {
      return new HttpResponse(["error" => $exception->getMessage()], HttpStatusCodes::BAD_REQUEST);
    } catch (OldPasswordIsWrongException $exception) {
      return new HttpResponse(["error" => $exception->getMessage()], HttpStatusCodes::BAD_REQUEST);
    }
  }
}