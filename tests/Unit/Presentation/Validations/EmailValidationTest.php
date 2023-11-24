<?php
use App\Core\Domain\Exceptions\InvalidFieldException;
use App\Presentation\Validations\EmailValidation;
use App\Presentation\Validations\Interfaces\EmailValidator;

beforeEach(function() {
  $this->fieldName = "email";
  $this->emailValidatorMock = Mockery::mock(EmailValidator::class);
  $this->sut = new EmailValidation($this->fieldName, $this->emailValidatorMock);
});

afterEach(function() {
  Mockery::close();
});

it('should return an InvalidFieldException because emailValidator returned false', function() {
  $this->emailValidatorMock->shouldReceive('isValid')->andReturn(false);

  $input = ["email" => "someEmail"];
  $result = $this->sut->validate($input);
  
  expect($result)->toBeInstanceOf(InvalidFieldException::class);
});

it('should return null because emailValidator returned true', function() {
  $this->emailValidatorMock->shouldReceive('isValid')->andReturn(true);

  $input = ["email" => "someEmail@mail.com"];
  $result = $this->sut->validate($input);
  
  expect($result)->toBeNull();
});
