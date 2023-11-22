<?php

namespace App\Core\Domain\Traits;
use App\Core\Domain\Entities\AccessToken\AccessToken;
use App\Core\Domain\Entities\AccessToken\AccessTokenRepository;
use App\Core\Domain\Entities\AccessToken\AccessTokenIntent;
use DateTime;

class UniqueTokenGeneratorTrait {
  private function generateUniqueToken(string|int $userId, AccessTokenRepository $accessTokenRepository, AccessTokenIntent $intent, int $timeToLeaveInSeconds): AccessToken {
    $accessToken = new AccessToken($intent, $userId, $timeToLeaveInSeconds, new DateTime(), null);

    while ($accessTokenRepository->find($accessToken->getToken())) {
      $accessToken->generateNewToken();
    }

    return $accessToken;
  }
}