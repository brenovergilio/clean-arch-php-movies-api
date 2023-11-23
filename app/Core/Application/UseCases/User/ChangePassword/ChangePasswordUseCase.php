<?php

namespace App\Core\Application\UseCases\User\ChangePassword;
use App\Core\Application\Exceptions\OldPasswordIsWrongException;
use App\Core\Application\Exceptions\PasswordAndConfirmationMismatchException;
use App\Core\Application\Interfaces\HashComparer;
use App\Core\Application\Interfaces\HashGenerator;
use App\Core\Application\UseCases\BaseUseCase;
use App\Core\Application\UseCases\User\ChangePassword\DTO\ChangePasswordInputDTO;
use App\Core\Domain\Entities\User\User;
use App\Core\Domain\Entities\User\UserRepository;
use App\Core\Domain\Exceptions\EntityNotFoundException;

class ChangePasswordUseCase extends BaseUseCase {
  public function __construct(
    private UserRepository $userRepository,
    private HashGenerator $hashGenerator,
    private HashComparer $hashComparer,
    private User $loggedUser
  ) {}

  public function execute(ChangePasswordInputDTO $input): void {
    $this->checkSameUser($this->loggedUser, $input->id);

    if($input->newPassword !== $input->newPasswordConfirmation) throw new PasswordAndConfirmationMismatchException;

    $user = $this->userRepository->findByID($input->id);
    if(!$user) throw new EntityNotFoundException(User::CLASS_NAME);

    $oldPasswordIsRight = $this->hashComparer->compare($user->password(), $input->oldPassword);
    if(!$oldPasswordIsRight) throw new OldPasswordIsWrongException;

    $newHashedPassword = $this->hashGenerator->generate($input->newPassword);
    $user->changePassword($newHashedPassword);

    $this->userRepository->update($user);
  }
}