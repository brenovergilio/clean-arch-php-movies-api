<?php

namespace App\Presentation\Http\Controllers\User;
use App\Core\Application\Exceptions\DuplicatedUniqueFieldException;
use App\Core\Application\Exceptions\PasswordAndConfirmationMismatchException;
use App\Core\Application\UseCases\User\CreateUser\CreateUserUseCase;
use App\Core\Application\UseCases\User\CreateUser\DTO\CreateUserInputDTO;
use App\Core\Domain\Exceptions\InvalidFieldException;
use App\Core\Domain\Exceptions\MissingRequiredFieldException;
use App\Core\Domain\Exceptions\WeakPasswordException;
use App\Presentation\Http\HttpStatusCodes;
use App\Presentation\Http\HttpRequest;
use App\Presentation\Http\HttpResponse;

class StoreUserController {
  public function __construct(private CreateUserUseCase $useCase) {}
  public function store(HttpRequest $request): HttpResponse {
    try {
      $validationException = UserControllerValidations::createUserValidations(array_keys($request->body))->validate($request->body);

      if($validationException) throw $validationException;

      $inputDto = new CreateUserInputDTO(
        $request->body['name'],
        $request->body['email'],
        $request->body['cpf'] ?? null,
        $request->body['password'],
        $request->body['passwordConfirmation'],
        $request->body['photo'] ?? null
      );

      $this->useCase->execute($inputDto);
      return new HttpResponse(null, HttpStatusCodes::CREATED);
    } catch (PasswordAndConfirmationMismatchException $exception) {
      return new HttpResponse(["error" => $exception->getMessage()], HttpStatusCodes::BAD_REQUEST);
    } catch (MissingRequiredFieldException $exception) {
      return new HttpResponse(["error" => $exception->getMessage()], HttpStatusCodes::BAD_REQUEST);
    } catch (WeakPasswordException $exception) {
      return new HttpResponse(["error" => $exception->getMessage()], HttpStatusCodes::BAD_REQUEST);
    } catch (InvalidFieldException $exception) {
      return new HttpResponse(["error" => $exception->getMessage()], HttpStatusCodes::BAD_REQUEST);
    } catch (DuplicatedUniqueFieldException $exception) {
      return new HttpResponse(["error" => $exception->getMessage()], HttpStatusCodes::CONFLICT);
    }
  }
}