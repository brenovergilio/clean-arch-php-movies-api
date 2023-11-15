<?php

namespace App\Core\Domain\Entities\AccessToken;
use App\Core\Domain\Helpers;
use DateTime;

class AccessToken {

  public function __construct(
    private AccessTokenIntent $intent,
    private string|int $relatedUserId,
    private int $timeToLeave,
    private DateTime $createdAt,
    private ?string $token
  ) {
    if(!isset($token)) { 
      $this->token = $this->generateNewToken();
    }
  }

  public function getToken(): ?string {
    return $this->token;
  }

  public function generateNewToken(): string {
    $tokenLength = 6;
    return Helpers::generateRandomString($tokenLength);
  }

  public function isExpired(): bool
  {
    return $this->createdAt->getTimestamp() + $this->timeToLeave < time();
  }
}