<?php
use App\Presentation\Validations\Adapters\CPFValidatorAdapter;

beforeEach(function() {
  $this->sut = new CPFValidatorAdapter();
});

it("should return false because CPF is invalid", function() {
  expect($this->sut->isValid("11111111111"))->toBeFalse();
});

it("should return true because CPF is valid", function() {
  expect($this->sut->isValid("14629037039"))->toBeTrue();
});