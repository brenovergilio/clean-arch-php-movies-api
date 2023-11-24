<?php
use App\Presentation\Validations\Adapters\EmailValidatorAdapter;

beforeEach(function() {
  $this->sut = new EmailValidatorAdapter();
});

it("should return false because email is invalid", function() {
  expect($this->sut->isValid("invalid"))->toBeFalse();
});

it("should return true because email is valid", function() {
  expect($this->sut->isValid("valid@mail.com"))->toBeTrue();
});