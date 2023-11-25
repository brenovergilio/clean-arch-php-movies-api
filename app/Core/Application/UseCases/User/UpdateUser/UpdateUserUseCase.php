<?php

namespace App\Core\Application\UseCases\User\UpdateUser;

use App\Core\Application\Exceptions\DuplicatedUniqueFieldException;
use App\Core\Application\Interfaces\EmailSender;
use App\Core\Application\Interfaces\FileManipulator;
use App\Core\Application\Interfaces\Folders;
use App\Core\Application\UseCases\BaseUseCase;
use App\Core\Application\UseCases\User\UpdateUser\DTO\UpdateUserInputDTO;
use App\Core\Domain\Entities\AccessToken\AccessTokenIntent;
use App\Core\Domain\Entities\AccessToken\AccessTokenRepository;
use App\Core\Domain\Entities\User\Email;
use App\Core\Domain\Entities\User\CPF;
use App\Core\Domain\Entities\User\User;
use App\Core\Domain\Entities\User\UserRepository;
use App\Core\Domain\Traits\EmailAccessTokenSenderTrait;

class UpdateUserUseCase extends BaseUseCase {
  public function __construct(
    private UserRepository $userRepository,
    private AccessTokenRepository $accessTokenRepository,
    private EmailSender $emailSender,
    private FileManipulator $fileManipulator,
    private User $loggedUser
  ) {}

  use EmailAccessTokenSenderTrait;

  public function execute(UpdateUserInputDTO $input): void {
    $this->validateUniqueness($input);

    $emailHasChanged = $input->email && $input->email !== $this->loggedUser->email();

    $this->mergeProperties($input, $this->loggedUser);

    $this->userRepository->update($this->loggedUser);

    if($emailHasChanged) $this->handleAccessTokenSending($this->loggedUser, AccessTokenIntent::CONFIRM_EMAIL);
  }

  private function validateUniqueness(UpdateUserInputDTO $input): void {
    if($input->email) {
      $userByEmail = $this->userRepository->findByEmail($input->email);
      if($userByEmail && $userByEmail->id() !== $this->loggedUser->id()) throw new DuplicatedUniqueFieldException(Email::CLASS_NAME);
    }

    if($input->cpf) {
      $userByCPF = $this->userRepository->findByCPF($input->cpf);
      if($userByCPF && $userByCPF->id() !== $this->loggedUser->id()) throw new DuplicatedUniqueFieldException(CPF::CLASS_NAME);
    }
  }

  private function mergeProperties(UpdateUserInputDTO $input, User $user): void
  {
    if($input->email) $user->changeEmail($input->email);

    if($input->cpf) $user->changeCPF($input->cpf);

    if($input->name) $user->changeName($input->name);

    if($input->photo) {
      $oldPhotoExists = $this->loggedUser->photo() && $this->fileManipulator->exists($this->loggedUser->photo());

      if($oldPhotoExists) $this->fileManipulator->delete($this->loggedUser->photo());
      
      $user->changePhoto($input->photo?->upload(Folders::USERS));
    }
  }
}