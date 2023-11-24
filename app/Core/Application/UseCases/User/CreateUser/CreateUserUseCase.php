<?php

namespace App\Core\Application\UseCases\User\CreateUser;

use App\Core\Application\Exceptions\DuplicatedUniqueFieldException;
use App\Core\Application\Exceptions\PasswordAndConfirmationMismatchException;
use App\Core\Application\Interfaces\HashGenerator;
use App\Core\Application\Interfaces\EmailSender;
use App\Core\Application\UseCases\BaseUseCase;
use App\Core\Application\UseCases\User\CreateUser\DTO\CreateUserInputDTO;
use App\Core\Domain\Entities\AccessToken\AccessTokenIntent;
use App\Core\Domain\Entities\AccessToken\AccessTokenRepository;
use App\Core\Domain\Entities\User\Email;
use App\Core\Domain\Entities\User\CPF;
use App\Core\Domain\Entities\User\Role;
use App\Core\Domain\Entities\User\User;
use App\Core\Domain\Entities\User\UserRepository;
use App\Core\Domain\Traits\EmailAccessTokenSenderTrait;

class CreateUserUseCase extends BaseUseCase {
  public function __construct(
    private UserRepository $userRepository,
    private AccessTokenRepository $accessTokenRepository,
    private HashGenerator $hashGenerator,
    private EmailSender $emailSender
  ) {}

  use EmailAccessTokenSenderTrait;

  public function execute(CreateUserInputDTO $input): void {
    $this->validateUniqueness($input);

    if($input->password !== $input->passwordConfirmation) throw new PasswordAndConfirmationMismatchException;

    $photoPath = $input->photo?->upload();

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

    $userFromDB = $this->userRepository->create($user, true);

    $this->handleAccessTokenSending($userFromDB, AccessTokenIntent::CONFIRM_EMAIL);
  }

  private function validateUniqueness(CreateUserInputDTO $input): void {
    $userByEmail = $this->userRepository->findByEmail($input->email);
    if($userByEmail) throw new DuplicatedUniqueFieldException(Email::CLASS_NAME);

    if($input->cpf) {
      $userByCPF = $this->userRepository->findByCPF($input->cpf);
      if($userByCPF) throw new DuplicatedUniqueFieldException(CPF::CLASS_NAME);
    }
  }
}