<?php
use App\Core\Domain\Exceptions\InvalidFieldException;
use App\Core\Domain\Exceptions\MissingRequiredFieldException;
use App\Presentation\Validations\CPFValidation;
use App\Presentation\Validations\EmailValidation;
use App\Presentation\Validations\RequiredFieldValidation;
use App\Presentation\Validations\ValidationComposite;

beforeEach(function() {
  $this->cpfValidation = Mockery::mock(CPFValidation::class);
  $this->emailValidation = Mockery::mock(EmailValidation::class);
  $this->requiredFieldValidation = Mockery::mock(RequiredFieldValidation::class);

  $this->sut = new ValidationComposite([
    $this->cpfValidation,
    $this->emailValidation,
    $this->requiredFieldValidation
  ]);
});

afterEach(function() {
  Mockery::close();
});

it("should return an InvalidFieldException because cpfValidation returned an exception", function() {
  $this->cpfValidation->shouldReceive('validate')->andReturn(new InvalidFieldException("cpf"));
  $this->emailValidation->shouldReceive('validate')->andReturn(null);
  $this->requiredFieldValidation->shouldReceive('validate')->andReturn(null);

  $result = $this->sut->validate(new stdClass);

  expect($result)->toBeInstanceOf(InvalidFieldException::class);
});

it("should return an InvalidFieldException because emailValidation returned an exception", function() {
  $this->emailValidation->shouldReceive('validate')->andReturn(new InvalidFieldException("email"));
  $this->cpfValidation->shouldReceive('validate')->andReturn(null);
  $this->requiredFieldValidation->shouldReceive('validate')->andReturn(null);

  $result = $this->sut->validate(new stdClass);
  
  expect($result)->toBeInstanceOf(InvalidFieldException::class);
});

it("should return a MissingRequiredField because requiredFieldValidation returned an exception", function() {
  $this->emailValidation->shouldReceive('validate')->andReturn(null);
  $this->cpfValidation->shouldReceive('validate')->andReturn(null);
  $this->requiredFieldValidation->shouldReceive('validate')->andReturn(new MissingRequiredFieldException("field"));

  $result = $this->sut->validate(new stdClass);
  
  expect($result)->toBeInstanceOf(MissingRequiredFieldException::class);
});

it("should return null because all validations returned null", function() {
  $this->emailValidation->shouldReceive('validate')->andReturn(null);
  $this->cpfValidation->shouldReceive('validate')->andReturn(null);
  $this->requiredFieldValidation->shouldReceive('validate')->andReturn(null);

  $result = $this->sut->validate(new stdClass);
  
  expect($result)->toBeNull();
});