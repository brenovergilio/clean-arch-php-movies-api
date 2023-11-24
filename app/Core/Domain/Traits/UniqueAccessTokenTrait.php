<?php

namespace App\Core\Domain\Traits;
use App\Core\Domain\Entities\AccessToken\AccessToken;
use App\Core\Domain\Entities\AccessToken\AccessTokenIntent;
use DateTime;

trait UniqueAccessTokenTrait {
  private function generateUniqueAccessToken(string|int $userId, AccessTokenIntent $intent, int $timeToLeaveInSeconds): AccessToken {
    $accessToken = new AccessToken($intent, $userId, $timeToLeaveInSeconds, new DateTime(), null);

    while ($this->accessTokenRepository->find($accessToken->getToken())) {
      $accessToken->generateNewToken();
    }

    $this->accessTokenRepository->create($accessToken);
    return $accessToken;
  }
}