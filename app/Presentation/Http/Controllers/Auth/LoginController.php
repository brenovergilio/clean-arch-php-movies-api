<?php

namespace App\Presentation\Http\Controllers\Auth;
use App\Core\Application\Exceptions\InvalidCredentialsException;
use App\Core\Application\UseCases\Auth\Login\DTO\LoginInputDTO;
use App\Core\Application\UseCases\Auth\Login\LoginUseCase;
use App\Core\Domain\Exceptions\InvalidFieldException;
use App\Core\Domain\Exceptions\MissingRequiredFieldException;
use App\Presentation\Http\HttpRequest;
use App\Presentation\Http\HttpResponse;
use App\Presentation\Http\HttpStatusCodes;

class LoginController {
  public function __construct(private LoginUseCase $useCase) {}

  public function login(HttpRequest $request): HttpResponse {
    try {
      $validationException = AuthControllerValidations::loginValidations(array_keys($request->body))->validate($request->body);

      if($validationException) throw $validationException;

      $inputDto = new LoginInputDTO(
        $request->body['email'],
        $request->body['password'],
      );

      $result = $this->useCase->execute($inputDto);
      return new HttpResponse(["data" => $result], HttpStatusCodes::CREATED);
    } catch (MissingRequiredFieldException $exception) {
      return new HttpResponse(["error" => $exception->getMessage()], HttpStatusCodes::BAD_REQUEST);
    } catch (InvalidFieldException $exception) {
      return new HttpResponse(["error" => $exception->getMessage()], HttpStatusCodes::BAD_REQUEST);
    } catch (InvalidCredentialsException $exception) {
      return new HttpResponse(["error" => $exception->getMessage()], HttpStatusCodes::UNAUTHORIZED);
    }
  }
}