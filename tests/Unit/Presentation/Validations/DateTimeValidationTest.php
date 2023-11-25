<?php
use App\Core\Domain\Exceptions\InvalidFieldException;
use App\Presentation\Validations\DateTimeValidation;
use App\Presentation\Validations\Interfaces\DateTimeValidator;

beforeEach(function() {
  $this->fieldName = "date";
  $this->dateTimeValidatorMock = Mockery::mock(DateTimeValidator::class);
  $this->sut = new DateTimeValidation($this->fieldName, $this->dateTimeValidatorMock);
});

afterEach(function() {
  Mockery::close();
});

it('should return an InvalidFieldException because dateTimeValidator returned false', function() {
  $this->dateTimeValidatorMock->shouldReceive('isValid')->andReturn(false);

  $input = ["date" => "someDate"];
  $result = $this->sut->validate($input);
  
  expect($result)->toBeInstanceOf(InvalidFieldException::class);
});

it('should return null because dateTimeValidator returned true', function() {
  $this->dateTimeValidatorMock->shouldReceive('isValid')->andReturn(true);

  $input = ["date" => "someDate"];
  $result = $this->sut->validate($input);
  
  expect($result)->toBeNull();
});
