<?php

namespace App\Core\Application\UseCases\User\FindUser;
use App\Core\Application\UseCases\BaseUseCase;
use App\Core\Application\UseCases\User\FindUser\DTO\FindUserInputDTO;
use App\Core\Application\UseCases\User\FindUser\DTO\FindUserOutputDTO;
use App\Core\Domain\Entities\User\UserRepository;
use App\Core\Domain\Exceptions\EntityNotFoundException;
use App\Core\Domain\Entities\User\User;

class FindUserUseCase extends BaseUseCase {
  public function __construct(
    private UserRepository $userRepository
  ) {}

  public function execute(FindUserInputDTO $input): FindUserOutputDTO {
    $user = $this->userRepository->findByID($input->id);

    if(!$user) throw new EntityNotFoundException(User::CLASS_NAME);

    return $this->mapToOutput($user);
  }

  private function mapToOutput(User $user): FindUserOutputDTO {
    return new FindUserOutputDTO(
      $user->id(),
      $user->name(),
      $user->cpf(),
      $user->email(),
      $user->photo()  
    );
  }
}
