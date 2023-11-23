<?php

namespace App\Core\Domain\Entities\AccessToken;
use App\Core\Domain\Entities\AccessToken\AccessToken;

interface AccessTokenRepository {
  public function create(AccessToken $accessToken): void;
  public function find(string $token): ?AccessToken;
  public function delete(string $token): void;
}