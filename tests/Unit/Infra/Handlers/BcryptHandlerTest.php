<?php
use App\Infra\Handlers\BcryptHandler;

beforeEach(function() {
  $this->sut = new BcryptHandler();
  $this->hashedValue = password_hash("password", PASSWORD_BCRYPT);
});

it('should return false because string does not match hashed value', function() {
  $originalString = 'doesNotMatch';

  $result = $this->sut->compare($originalString, $this->hashedValue);

  expect($result)->toBeFalse();
});

it('should return true because string does match hashed value', function() {
  $originalString = 'password';

  $result = $this->sut->compare($originalString, $this->hashedValue);

  expect($result)->toBeTrue();
});

it('should hash a string and match it', function() {
  $originalString = 'stringToHash';
  $hashedString = $this->sut->generate($originalString);

  $result = $this->sut->compare($originalString, $hashedString);

  expect($result)->toBeTrue();
});