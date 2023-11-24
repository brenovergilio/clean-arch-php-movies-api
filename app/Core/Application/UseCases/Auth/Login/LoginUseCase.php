<?php

namespace App\Core\Application\UseCases\Auth\Login;
use App\Core\Application\Exceptions\InvalidCredentialsException;
use App\Core\Application\Interfaces\HashComparer;
use App\Core\Application\Interfaces\TokenGenerator;
use App\Core\Application\UseCases\Auth\Login\DTO\LoginInputDTO;
use App\Core\Application\UseCases\Auth\Login\DTO\LoginOutputDTO;
use App\Core\Application\UseCases\BaseUseCase;
use App\Core\Domain\Entities\User\UserRepository;

class LoginUseCase extends BaseUseCase {
  public function __construct(
    private UserRepository $userRepository,
    private HashComparer $hashComparer,
    private TokenGenerator $tokenGenerator
  ) {}

  public function execute(LoginInputDTO $input): LoginOutputDTO {
    $user = $this->userRepository->findByEmail($input->email);
    if(!$user) throw new InvalidCredentialsException();

    $passwordMatch = $this->hashComparer->compare($user->password(), $input->password);
    if(!$passwordMatch) throw new InvalidCredentialsException;

    $token = $this->tokenGenerator->generate($user, ['id']);

    return new LoginOutputDTO(
      $user->id(),
      $token,
      $user->isAdmin()
    );
  }
}