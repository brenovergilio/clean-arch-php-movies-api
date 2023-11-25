<?php
use App\Core\Domain\Exceptions\MissingRequiredFieldException;
use App\Presentation\Validations\RequiredFieldValidation;

beforeEach(function() {
  $this->fieldName = "field";
  $this->sut = new RequiredFieldValidation($this->fieldName);
});

it('should return an MissingRequiredFieldException because input does not have the provided value', function() {
  $input = [];
  $result = $this->sut->validate($input);
  
  expect($result)->toBeInstanceOf(MissingRequiredFieldException::class);
});

it('should return null because input does have the provided value', function() {
  $input = ["field" => "input"];
  $result = $this->sut->validate($input);
  
  expect($result)->toBeNull();
});
