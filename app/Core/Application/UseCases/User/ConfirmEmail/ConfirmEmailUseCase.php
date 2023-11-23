<?php

namespace App\Core\Application\UseCases\User\ConfirmEmail;
use App\Core\Application\UseCases\User\ConfirmEmail\DTO\ConfirmEmailInputDTO;
use App\Core\Domain\Entities\AccessToken\AccessToken;
use App\Core\Domain\Entities\AccessToken\AccessTokenIntent;
use App\Core\Domain\Entities\AccessToken\AccessTokenRepository;
use App\Core\Domain\Entities\User\User;
use App\Core\Domain\Entities\User\UserRepository;
use App\Core\Domain\Exceptions\EntityNotFoundException;

class ConfirmEmailUseCase {
  public function __construct(
    private UserRepository $userRepository,
    private AccessTokenRepository $accessTokenRepository
  ) {}

  public function execute(ConfirmEmailInputDTO $input): void {
    $accessToken = $this->accessTokenRepository->find($input->accessTokenValue);
    
    if(!$accessToken) throw new EntityNotFoundException(AccessToken::CLASS_NAME);
    if($accessToken->getIntent() !== AccessTokenIntent::CONFIRM_EMAIL) throw new EntityNotFoundException(AccessToken::CLASS_NAME);
    $accessToken->checkExpiration();

    $user = $this->userRepository->findByID($accessToken->getRelatedUserID());
    if(!$user) throw new EntityNotFoundException(User::CLASS_NAME);

    $user->confirmEmail();
    $this->userRepository->update($user);
    $this->accessTokenRepository->delete($accessToken->getToken());
  }
}