<?php
use App\Presentation\Validations\Adapters\DateTimeValidatorAdapter;

beforeEach(function() {
  $this->sut = new DateTimeValidatorAdapter();
});

it("should return false because date is invalid", function() {
  expect($this->sut->isValid("invalid"))->toBeFalse();
});

it("should return true because date is valid", function() {
  expect($this->sut->isValid("2000-01-01"))->toBeTrue();
});