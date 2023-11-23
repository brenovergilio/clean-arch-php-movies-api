<?php
use App\Core\Domain\Entities\AccessToken\AccessToken;
use App\Core\Domain\Entities\AccessToken\AccessTokenIntent;
 
it("should return the current token", function() { 
  $accessToken = new AccessToken(AccessTokenIntent::CONFIRM_EMAIL, "userId", 60*60, new DateTime(), "123456");
  expect($accessToken->getToken())->toBe("123456");
});

it("should return token intent", function() { 
  $accessToken = new AccessToken(AccessTokenIntent::CONFIRM_EMAIL, "userId", 60*60, new DateTime(), "123456");
  expect($accessToken->getIntent())->toBe(AccessTokenIntent::CONFIRM_EMAIL);
});

it("should return related user ID", function() { 
  $accessToken = new AccessToken(AccessTokenIntent::CONFIRM_EMAIL, "userId", 60*60, new DateTime(), "123456");
  expect($accessToken->getRelatedUserID())->toBe("userId");
});

it("should generate a 6-length token because no token was provided", function() {
  $accessToken = new AccessToken(AccessTokenIntent::CONFIRM_EMAIL, "userId", 60*60, new DateTime(), null);
  expect($accessToken->getToken())->toBeString();
  expect(strlen($accessToken->getToken()))->toBe(6);
});

it("should generate a different 6-length token", function() {
  $accessToken = new AccessToken(AccessTokenIntent::CONFIRM_EMAIL, "userId", 60*60, new DateTime(), "123456");
  expect($accessToken->getToken())->toBe("123456");
  $accessToken->generateNewToken();
  expect($accessToken->getToken())->not->toBe("123456");
});

it("should return false when calling isExpired() because token is expired", function() { 
  $now = new DateTime();
  $oneHourAgo = $now->modify('-1 hour');
  $accessToken = new AccessToken(AccessTokenIntent::CONFIRM_EMAIL, "userId", 0, $oneHourAgo, "123456");
  expect($accessToken->isExpired())->toBe(true);
});

it("should return true when calling isExpired() because token is not expired", function() { 
  $accessToken = new AccessToken(AccessTokenIntent::CONFIRM_EMAIL, "userId", 60*60, new DateTime(), "123456");
  expect($accessToken->isExpired())->toBe(false);
});

it("should throw an ExpiredAccessTokenException when calling checkExpiration() because token is expired", function() { 
  $now = new DateTime();
  $oneHourAgo = $now->modify('-1 hour');
  $accessToken = new AccessToken(AccessTokenIntent::CONFIRM_EMAIL, "userId", 0, $oneHourAgo, "123456");
  expect(function() use ($accessToken){
    $accessToken->checkExpiration();
  })->toThrow("Access Token is expired");
});
