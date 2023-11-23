<?php

namespace App\Core\Domain\Traits;
use App\Core\Domain\Entities\AccessToken\AccessToken;
use App\Core\Domain\Entities\AccessToken\AccessTokenIntent;
use DateTime;

trait UniqueTokenGeneratorTrait {
  private function generateUniqueToken(string|int $userId, AccessTokenIntent $intent, int $timeToLeaveInSeconds): AccessToken {
    $accessToken = new AccessToken($intent, $userId, $timeToLeaveInSeconds, new DateTime(), null);

    while ($this->accessTokenRepository->find($accessToken->getToken())) {
      $accessToken->generateNewToken();
    }

    return $accessToken;
  }
}