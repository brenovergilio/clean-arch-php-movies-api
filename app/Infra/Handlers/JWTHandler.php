<?php

namespace App\Infra\Handlers;
use App\Core\Application\Interfaces\TokenDecoder;
use App\Core\Application\Interfaces\TokenGenerator;
use App\Core\Domain\Helpers;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JWTHandler implements TokenGenerator, TokenDecoder {
  private int $defaultExpiration;
  private string $secret;

  public function __construct()
  {
    $this->defaultExpiration = time() + Helpers::ONE_WEEK_IN_SECONDS;
    $this->secret = env("JWT_SECRET", "secret");
  }

  public function generate($target, array $fieldsToTokenize, ?int $expiration = null, $usingGetter = true): string {
    $filteredObject = [];

    foreach($fieldsToTokenize as $field) {
      if($usingGetter) {
        $filteredObject[$field] = $target->$field();
      } else {
        $filteredObject[$field] = $target->$field;
      }
    }

    $filteredObject['exp'] = isset($expiration) ? time () + $expiration : $this->defaultExpiration;

    return JWT::encode($filteredObject, $this->secret, "HS256");
  }

  public function decode(string $token)
  {
    return JWT::decode($token, new Key($this->secret, 'HS256'));
  }
}