<?php
use App\Core\Domain\Exceptions\InvalidFieldException;
use App\Presentation\Validations\PositiveNumberValidation;

beforeEach(function() {
  $this->fieldName = "number";
  $this->sut = new PositiveNumberValidation($this->fieldName);
});

it('should return an InvalidFieldException because number is not positive', function() {
  $input = ["number" => 0];
  $result = $this->sut->validate($input);
  
  expect($result)->toBeInstanceOf(InvalidFieldException::class);

  $input = ["number" => -1];
  $result = $this->sut->validate($input);
  
  expect($result)->toBeInstanceOf(InvalidFieldException::class);
});

it('should return null because number is positive', function() {
  $input = ["number" => 2];
  $result = $this->sut->validate($input);
  
  expect($result)->toBeNull();
});
