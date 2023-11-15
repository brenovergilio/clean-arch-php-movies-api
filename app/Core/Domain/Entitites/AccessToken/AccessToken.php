<?php

namespace App\Core\Domain\Entities\AccessToken;
use App\Core\Domain\Helpers;

class AccessToken {

  public function __construct(
    private AccessTokenIntent $intent,
    private ?string $token
  ) {
    if(!isset($token)) { 
      $this->token = $this->generateNewToken();
    }
  }

  public function generateNewToken(): string {
    return Helpers::generateRandomString(6);
  }
}