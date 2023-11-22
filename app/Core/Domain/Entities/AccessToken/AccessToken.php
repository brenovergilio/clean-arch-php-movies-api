<?php

namespace App\Core\Domain\Entities\AccessToken;
use App\Core\Domain\Entities\AccessToken\AccessTokenIntent;
use App\Core\Domain\Helpers;
use DateTime;

class AccessToken {

  public function __construct(
    private AccessTokenIntent $intent,
    private string|int $relatedUserId,
    private int $timeToLeaveInSeconds,
    private DateTime $createdAt,
    private ?string $token
  ) {
    if(!$token) { 
      $this->generateNewToken();
    }
  }

  public function getToken(): ?string {
    return $this->token;
  }

  public function generateNewToken(): void {
    $tokenLength = 6;

    do {
      $newToken = strtoupper(Helpers::generateRandomString($tokenLength));
    } while ($newToken === $this->token);

    $this->token = $newToken;
  }

  public function isExpired(): bool
  {
    return $this->createdAt->getTimestamp() + $this->timeToLeaveInSeconds < time();
  }
}