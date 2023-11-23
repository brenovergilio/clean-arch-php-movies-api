<?php
use App\Infra\Handlers\BcryptHandler;
use Illuminate\Support\Facades\Hash;

beforeEach(function() {
  $this->sut = new BcryptHandler();
  $this->hashedValue = Hash::make('password');
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