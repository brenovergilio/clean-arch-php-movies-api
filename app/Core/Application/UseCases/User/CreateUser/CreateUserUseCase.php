<?php

namespace App\Core\Application\UseCases\User\CreateUser;

use App\Core\Application\Exceptions\DuplicatedUniqueFieldException;
use App\Core\Application\Exceptions\PasswordAndConfirmationMismatchException;
use App\Core\Application\Interfaces\HashGenerator;
use App\Core\Application\Interfaces\EmailSender;
use App\Core\Application\UseCases\BaseUseCase;
use App\Core\Application\UseCases\User\CreateUser\DTO\CreateUserInputDTO;
use App\Core\Domain\Entities\AccessToken\AccessToken;
use App\Core\Domain\Entities\AccessToken\AccessTokenIntent;
use App\Core\Domain\Entities\AccessToken\AccessTokenRepository;
use App\Core\Domain\Entities\Role;
use App\Core\Domain\Entities\User\User;
use App\Core\Domain\Entities\User\UserRepository;
use App\Core\Domain\Helpers;
use DateTime;

class CreateUserUseCase extends BaseUseCase {
  public function __construct(
    private UserRepository $userRepository,
    private AccessTokenRepository $accessTokenRepository,
    private HashGenerator $hashGenerator,
    private EmailSender $emailSender
  ) {}

  public function execute(CreateUserInputDTO $input): void {
    $this->validateUniqueness($input);

    if($input->password !== $input->passwordConfirmation) throw new PasswordAndConfirmationMismatchException;

    $photoPath = $input->photo->upload();

    $hashedPassword = $this->hashGenerator->generate($input->password);

    $user = new User(
      null,
      $input->name,
      $input->cpf,
      $input->email,
      $hashedPassword,
      Role::CLIENT,
      $photoPath,
      false
    );

    $this->userRepository->create($user);

    $token = $this->generateNewToken($user->id());

    $this->emailSender->sendMail($input->email, "Confirm your account", [
      "token" => $token
    ]);
  }

  private function validateUniqueness(CreateUserInputDTO $input): void {
    $userByEmail = $this->userRepository->findByEmail($input->email);
    if($userByEmail) throw new DuplicatedUniqueFieldException("Email");

    $userByCPF = $this->userRepository->findByEmail($input->email);
    if($userByCPF) throw new DuplicatedUniqueFieldException("CPF");
  }

  private function generateNewToken(string|int $userId): AccessToken {
    $accessToken = new AccessToken(AccessTokenIntent::CONFIRM_EMAIL, $userId, Helpers::ONE_HOUR_IN_SECONDS, new DateTime(), null);

    while ($this->accessTokenRepository->find($accessToken->getToken())) {
      $accessToken->generateNewToken();
    }

    return $accessToken;
  }
}