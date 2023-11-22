<?php

namespace App\Core\Application\UseCases\User\CreateUser;

use App\Core\Application\Exceptions\DuplicatedUniqueFieldException;
use App\Core\Application\Interfaces\EmailSender;
use App\Core\Application\UseCases\BaseUseCase;
use App\Core\Application\UseCases\User\UpdateUser\DTO\UpdateUserInputDTO;
use App\Core\Domain\Entities\AccessToken\AccessTokenIntent;
use App\Core\Domain\Entities\AccessToken\AccessTokenRepository;
use App\Core\Domain\Entities\User\Email;
use App\Core\Domain\Entities\User\CPF;
use App\Core\Domain\Entities\User\User;
use App\Core\Domain\Entities\User\UserRepository;
use App\Core\Domain\Exceptions\EntityNotFoundException;
use App\Core\Domain\Traits\EmailTokenSenderTrait;

class UpdateUserUseCase extends BaseUseCase {
  public function __construct(
    private UserRepository $userRepository,
    private AccessTokenRepository $accessTokenRepository,
    private EmailSender $emailSender,
    private User $loggedUser
  ) {}

  use EmailTokenSenderTrait;

  public function execute(UpdateUserInputDTO $input): void {
    $this->checkSameUser($this->loggedUser, $input->id);
    $this->validateUniqueness($input);

    $user = $this->userRepository->findByID($input->id);

    if(!$user) throw new EntityNotFoundException(User::class);

    $emailHasChanged = $input->email !== $user->email();

    $this->mergeProperties($input, $user);

    $this->userRepository->update($user);

    if($emailHasChanged) $this->handleTokenSending($user, AccessTokenIntent::CONFIRM_EMAIL);
  }

  private function validateUniqueness(UpdateUserInputDTO $input): void {
    $userByEmail = $this->userRepository->findByEmail($input->email);
    if($userByEmail && $userByEmail->id() !== $this->loggedUser->id()) throw new DuplicatedUniqueFieldException(Email::class);

    $userByCPF = $this->userRepository->findByCPF($input->cpf);
    if($userByCPF && $userByCPF->id() !== $this->loggedUser->id()) throw new DuplicatedUniqueFieldException(CPF::class);
  }

  private function mergeProperties(UpdateUserInputDTO $input, User $user): void
  {
    if($input->email) $user->changeEmail($input->email);

    if($input->cpf) $user->changeCPF($input->cpf);

    if($input->name) $user->changeName($input->name);

    if($input->photo) $user->changePhoto($input->photo?->upload());
  }
}