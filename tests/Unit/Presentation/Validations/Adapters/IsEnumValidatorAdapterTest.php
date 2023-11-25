<?php
use App\Core\Domain\Entities\Movie\MovieGenre;
use App\Presentation\Validations\Adapters\IsEnumValidatorAdapter;

beforeEach(function() {
  $this->sut = new IsEnumValidatorAdapter();
});

it("should return false because input is not in the provided enum", function() {
  expect($this->sut->isValid("notIn", MovieGenre::class))->toBeFalse();
});

it("should return true because input is in the provided enum", function() {
  expect($this->sut->isValid("comedy", MovieGenre::class))->toBeTrue();
});
