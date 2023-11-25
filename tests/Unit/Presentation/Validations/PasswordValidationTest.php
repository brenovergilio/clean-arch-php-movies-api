<?php
use App\Core\Domain\Exceptions\WeakPasswordException;
use App\Presentation\Validations\PasswordValidation;

beforeEach(function() {
  $this->fieldName = "password";
  $this->sut = new PasswordValidation($this->fieldName);
});

it('should return an WeakPasswordException because it does not match regex', function() {
  $input = ["password" => "abc"];
  $result = $this->sut->validate($input);
  
  expect($result)->toBeInstanceOf(WeakPasswordException::class);
});

it('should return null because password is strong', function() {
  $input = ["password" => "SenhaFort3#1."];
  $result = $this->sut->validate($input);
  
  expect($result)->toBeNull();
});
