<?php

namespace App\Core\Application\UseCases\User\CreateUser;

use App\Core\Application\Exceptions\DuplicatedUniqueFieldException;
use App\Core\Application\Exceptions\PasswordAndConfirmationMismatchException;
use App\Core\Application\Interfaces\HashGenerator;
use App\Core\Application\UseCases\BaseUseCase;
use App\Core\Application\UseCases\User\CreateUser\DTO\CreateUserInputDTO;
use App\Core\Domain\Entities\Role;
use App\Core\Domain\Entities\User\User;
use App\Core\Domain\Entities\User\UserRepository;

class CreateUserUseCase extends BaseUseCase {
  public function __construct(
    private UserRepository $userRepository,
    private HashGenerator $hashGenerator
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
  }

  private function validateUniqueness(CreateUserInputDTO $input): void {
    $userByEmail = $this->userRepository->findByEmail($input->email);
    if($userByEmail) throw new DuplicatedUniqueFieldException("Email");

    $userByCPF = $this->userRepository->findByEmail($input->email);
    if($userByCPF) throw new DuplicatedUniqueFieldException("CPF");
  }
}