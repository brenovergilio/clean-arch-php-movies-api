<?php

namespace App\Presentation\Http\Controllers\User;
use App\Core\Application\Exceptions\DuplicatedUniqueFieldException;
use App\Core\Application\Exceptions\InsufficientPermissionsException;
use App\Core\Application\Exceptions\OldPasswordIsWrongException;
use App\Core\Application\Exceptions\PasswordAndConfirmationMismatchException;
use App\Core\Application\UseCases\User\ChangePassword\DTO\ChangePasswordInputDTO;
use App\Core\Application\UseCases\User\ConfirmEmail\DTO\ConfirmEmailInputDTO;
use App\Core\Application\UseCases\User\CreateUser\DTO\CreateUserInputDTO;
use App\Core\Application\UseCases\User\FindUser\DTO\FindUserInputDTO;
use App\Core\Application\UseCases\User\UpdateUser\DTO\UpdateUserInputDTO;
use App\Core\Domain\Entities\User\User;
use App\Core\Domain\Exceptions\EntityNotFoundException;
use App\Core\Domain\Exceptions\InvalidFieldException;
use App\Core\Domain\Exceptions\MissingRequiredFieldException;
use App\Core\Domain\Exceptions\WeakPasswordException;
use App\Infra\Factories\UseCases\User\ChangePasswordUseCaseFactory;
use App\Infra\Factories\UseCases\User\ConfirmEmailUseCaseFactory;
use App\Infra\Factories\UseCases\User\CreateUserUseCaseFactory;
use App\Infra\Factories\UseCases\User\FindUserUseCaseFactory;
use App\Infra\Factories\UseCases\User\UpdateUserUseCaseFactory;
use App\Presentation\Http\HttpStatusCodes;
use App\Presentation\Http\HttpRequest;
use App\Presentation\Http\HttpResponse;

class UserController {
  public function __construct(private User $loggedUser) {}
  public function store(HttpRequest $request): HttpResponse {
    try {
      $useCase = CreateUserUseCaseFactory::make();

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

      $useCase->execute($inputDto);
      return new HttpResponse(null, HttpStatusCodes::NO_CONTENT);
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

  public function update(string|int $id, HttpRequest $request): HttpResponse {
    try {
      $useCase = UpdateUserUseCaseFactory::make($this->loggedUser);

      $validationException = UserControllerValidations::updateUserValidations(array_keys($request->body))->validate($request->body);

      if($validationException) throw $validationException;

      $inputDto = new UpdateUserInputDTO(
        $id,
        $request->body['name'],
        $request->body['email'],
        $request->body['cpf'],
        $request->body['photo']
      );

      $useCase->execute($inputDto);
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

  public function show(string|int $id): HttpResponse {
    try {
      $useCase = FindUserUseCaseFactory::make();

      $inputDto = new FindUserInputDTO(
        $id
      );

      $result = $useCase->execute($inputDto);
      return new HttpResponse($result, HttpStatusCodes::OK);
    } catch (EntityNotFoundException $exception) {
      return new HttpResponse(["error" => $exception->getMessage()], HttpStatusCodes::NOT_FOUND);
    }
  }

  public function changePassword(HttpRequest $request): HttpResponse {
    try {
      $useCase = ChangePasswordUseCaseFactory::make($this->loggedUser);

      $validationException = UserControllerValidations::changePasswordValidations()->validate($request->body);

      if($validationException) throw $validationException;

      $inputDto = new ChangePasswordInputDTO(
        $request->body['newPassword'],
        $request->body['newPasswordConfirmation'],
        $request->body['oldPassword'],
      );

      $useCase->execute($inputDto);
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

  public function confirmEmail(HttpRequest $request): HttpResponse {
    try {
      $useCase = ConfirmEmailUseCaseFactory::make($this->loggedUser);

      $validationException = UserControllerValidations::confirmEmailValidations()->validate($request->body);

      if($validationException) throw $validationException;

      $inputDto = new ConfirmEmailInputDTO(
        $request->body['accessToken']
      );

      $useCase->execute($inputDto);
      return new HttpResponse(null, HttpStatusCodes::NO_CONTENT);
    } catch (MissingRequiredFieldException $exception) {
      return new HttpResponse(["error" => $exception->getMessage()], HttpStatusCodes::BAD_REQUEST);
    } catch (EntityNotFoundException $exception) {
      return new HttpResponse(["error" => $exception->getMessage()], HttpStatusCodes::NOT_FOUND);
    }
  }
}