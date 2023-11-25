<?php
use App\Core\Domain\Exceptions\InvalidFieldException;
use App\Presentation\Validations\Interfaces\IsEnumValidator;
use App\Presentation\Validations\IsEnumValidation;

beforeEach(function() {
  $this->fieldName = "enum";
  $this->isEnumValidatorMock = Mockery::mock(IsEnumValidator::class);
  $this->sut = new IsEnumValidation($this->fieldName, $this->isEnumValidatorMock, "someEnum");
});

afterEach(function() {
  Mockery::close();
});

it('should return an InvalidFieldException because isEnumValidator returned false', function() {
  $this->isEnumValidatorMock->shouldReceive('isValid')->andReturn(false);

  $input = ["enum" => "someEnum"];
  $result = $this->sut->validate($input);
  
  expect($result)->toBeInstanceOf(InvalidFieldException::class);
});

it('should return null because isEnumValidator returned true', function() {
  $this->isEnumValidatorMock->shouldReceive('isValid')->andReturn(true);

  $input = ["enum" => "someEnum"];
  $result = $this->sut->validate($input);
  
  expect($result)->toBeNull();
});
