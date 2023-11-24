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
use App\Infra\Factories\UseCases\User\ChangePasswordUseCaseFactory;
use App\Infra\Factories\UseCases\User\ConfirmEmailUseCaseFactory;
use App\Infra\Factories\UseCases\User\CreateUserUseCaseFactory;
use App\Infra\Factories\UseCases\User\FindUserUseCaseFactory;
use App\Infra\Factories\UseCases\User\UpdateUserUseCaseFactory;
use App\Presentation\Http\HttpStatusCodes;
use App\Presentation\Http\Interfaces\HttpRequest;
use App\Presentation\Http\Interfaces\HttpResponse;

class UserController {
  public function __construct(private User $loggedUser) {}
  public function store(HttpRequest $request): HttpResponse {
    try {
      $useCase = CreateUserUseCaseFactory::make();

      $inputDto = new CreateUserInputDTO(
        $request->body?->name,
        $request->body?->email,
        $request->body?->cpf,
        $request->body?->password,
        $request->body?->confirmationPassword,
        $request->body?->photo
      );

      $useCase->execute($inputDto);
      return new HttpResponse(null, HttpStatusCodes::NO_CONTENT);
    } catch (PasswordAndConfirmationMismatchException $exception) {
      return new HttpResponse($exception->getMessage(), HttpStatusCodes::BAD_REQUEST);
    } catch (DuplicatedUniqueFieldException $exception) {
      return new HttpResponse($exception->getMessage(), HttpStatusCodes::CONFLICT);
    }
  }

  public function update(string|int $id, HttpRequest $request): HttpResponse {
    try {
      $useCase = UpdateUserUseCaseFactory::make($this->loggedUser);

      $inputDto = new UpdateUserInputDTO(
        $id,
        $request->body?->name,
        $request->body?->email,
        $request->body?->cpf,
        $request->body?->photo
      );

      $useCase->execute($inputDto);
      return new HttpResponse(null, HttpStatusCodes::NO_CONTENT);
    } catch (EntityNotFoundException $exception) {
      return new HttpResponse($exception->getMessage(), HttpStatusCodes::NOT_FOUND);
    } catch (DuplicatedUniqueFieldException $exception) {
      return new HttpResponse($exception->getMessage(), HttpStatusCodes::CONFLICT);
    } catch (InsufficientPermissionsException $exception) {
      return new HttpResponse($exception->getMessage(), HttpStatusCodes::FORBIDDEN);
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
      return new HttpResponse($exception->getMessage(), HttpStatusCodes::NOT_FOUND);
    }
  }

  public function changePassword(HttpRequest $request): HttpResponse {
    try {
      $useCase = ChangePasswordUseCaseFactory::make($this->loggedUser);

      $inputDto = new ChangePasswordInputDTO(
        $request->body?->newPassword,
        $request->body?->newPasswordConfirmation,
        $request->body?->oldPassword,
      );

      $useCase->execute($inputDto);
      return new HttpResponse(null, HttpStatusCodes::NO_CONTENT);
    } catch (PasswordAndConfirmationMismatchException $exception) {
      return new HttpResponse($exception->getMessage(), HttpStatusCodes::BAD_REQUEST);
    } catch (OldPasswordIsWrongException $exception) {
      return new HttpResponse($exception->getMessage(), HttpStatusCodes::BAD_REQUEST);
    }
  }

  public function confirmEmail(HttpRequest $request): HttpResponse {
    try {
      $useCase = ConfirmEmailUseCaseFactory::make($this->loggedUser);

      $inputDto = new ConfirmEmailInputDTO(
        $request->body?->accessToken
      );

      $useCase->execute($inputDto);
      return new HttpResponse(null, HttpStatusCodes::NO_CONTENT);
    } catch (EntityNotFoundException $exception) {
      return new HttpResponse($exception->getMessage(), HttpStatusCodes::NOT_FOUND);
    }
  }
}