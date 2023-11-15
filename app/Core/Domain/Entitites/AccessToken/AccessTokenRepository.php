<?php

namespace App\Core\Domain\Entities\AccessToken;

interface AccessTokenRepository {
  public function create(AccessToken $accessToken): void;
  public function find(string $token): AccessToken;
}