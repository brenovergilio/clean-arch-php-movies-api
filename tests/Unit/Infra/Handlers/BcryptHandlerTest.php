<?php
use App\Infra\Handlers\BcryptHandler;

beforeEach(function() {
  $this->sut = new BcryptHandler();
  $this->hashedValue = '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi';
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