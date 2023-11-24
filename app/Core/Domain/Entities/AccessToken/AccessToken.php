<?php

namespace App\Core\Domain\Entities\AccessToken;
use App\Core\Domain\Entities\AccessToken\AccessTokenIntent;
use App\Core\Domain\Exceptions\ExpiredAccessTokenException;
use App\Core\Domain\Helpers;
use DateTime;

class AccessToken {

  const CLASS_NAME = "AccessToken";
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

  public function getRelatedUserID(): string|int {
    return $this->relatedUserId;
  }

  public function getIntent(): AccessTokenIntent {
    return $this->intent;
  }

  public function getTimeToLeave(): int {
    return $this->timeToLeaveInSeconds;
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

  public function checkExpiration(): void {
    if($this->isExpired()) throw new ExpiredAccessTokenException;
  }
}