<?php
use App\Core\Domain\Exceptions\InvalidFieldException;
use App\Core\Domain\Exceptions\MissingRequiredFieldException;
use App\Presentation\Validations\CPFValidation;
use App\Presentation\Validations\DateTimeValidation;
use App\Presentation\Validations\EmailValidation;
use App\Presentation\Validations\Exceptions\InvalidOrderInputException;
use App\Presentation\Validations\InstanceOfValidation;
use App\Presentation\Validations\IsEnumValidation;
use App\Presentation\Validations\OrderInputValidation;
use App\Presentation\Validations\PasswordValidation;
use App\Presentation\Validations\PrimitiveTypeValidation;
use App\Presentation\Validations\RequiredFieldValidation;
use App\Presentation\Validations\ValidationComposite;

beforeEach(function() {
  $this->cpfValidation = Mockery::mock(CPFValidation::class);
  $this->emailValidation = Mockery::mock(EmailValidation::class);
  $this->requiredFieldValidation = Mockery::mock(RequiredFieldValidation::class);
  $this->dateTimeValidation = Mockery::mock(DateTimeValidation::class);
  $this->instanceOfValidation = Mockery::mock(InstanceOfValidation::class);
  $this->isEnumValidation = Mockery::mock(IsEnumValidation::class);
  $this->passwordValidation = Mockery::mock(PasswordValidation::class);
  $this->primitiveTypeValidation = Mockery::mock(PrimitiveTypeValidation::class);
  $this->orderInputValidation = Mockery::mock(OrderInputValidation::class);

  $this->sut = new ValidationComposite([
    $this->cpfValidation,
    $this->emailValidation,
    $this->requiredFieldValidation,
    $this->dateTimeValidation,
    $this->instanceOfValidation,
    $this->isEnumValidation,
    $this->passwordValidation,
    $this->primitiveTypeValidation,
    $this->orderInputValidation
  ]);
});

afterEach(function() {
  Mockery::close();
});

it("should return an InvalidFieldException because cpfValidation returned an exception", function() {
  $this->cpfValidation->shouldReceive('validate')->andReturn(new InvalidFieldException("cpf"));
  $this->emailValidation->shouldReceive('validate')->andReturn(null);
  $this->requiredFieldValidation->shouldReceive('validate')->andReturn(null);
  $this->dateTimeValidation->shouldReceive('validate')->andReturn(null);
  $this->instanceOfValidation->shouldReceive('validate')->andReturn(null);
  $this->isEnumValidation->shouldReceive('validate')->andReturn(null);
  $this->passwordValidation->shouldReceive('validate')->andReturn(null);
  $this->primitiveTypeValidation->shouldReceive('validate')->andReturn(null);
  $this->orderInputValidation->shouldReceive('validate')->andReturn(null);

  $result = $this->sut->validate(new stdClass);

  expect($result)->toBeInstanceOf(InvalidFieldException::class);
});

it("should return an InvalidFieldException because emailValidation returned an exception", function() {
  $this->emailValidation->shouldReceive('validate')->andReturn(new InvalidFieldException("email"));
  $this->cpfValidation->shouldReceive('validate')->andReturn(null);
  $this->requiredFieldValidation->shouldReceive('validate')->andReturn(null);
  $this->dateTimeValidation->shouldReceive('validate')->andReturn(null);
  $this->instanceOfValidation->shouldReceive('validate')->andReturn(null);
  $this->isEnumValidation->shouldReceive('validate')->andReturn(null);
  $this->passwordValidation->shouldReceive('validate')->andReturn(null);
  $this->primitiveTypeValidation->shouldReceive('validate')->andReturn(null);
  $this->orderInputValidation->shouldReceive('validate')->andReturn(null);

  $result = $this->sut->validate(new stdClass);
  
  expect($result)->toBeInstanceOf(InvalidFieldException::class);
});

it("should return a MissingRequiredField because requiredFieldValidation returned an exception", function() {
  $this->emailValidation->shouldReceive('validate')->andReturn(null);
  $this->cpfValidation->shouldReceive('validate')->andReturn(null);
  $this->requiredFieldValidation->shouldReceive('validate')->andReturn(new MissingRequiredFieldException("field"));
  $this->dateTimeValidation->shouldReceive('validate')->andReturn(null);
  $this->instanceOfValidation->shouldReceive('validate')->andReturn(null);
  $this->isEnumValidation->shouldReceive('validate')->andReturn(null);
  $this->passwordValidation->shouldReceive('validate')->andReturn(null);
  $this->primitiveTypeValidation->shouldReceive('validate')->andReturn(null);
  $this->orderInputValidation->shouldReceive('validate')->andReturn(null);

  $result = $this->sut->validate(new stdClass);
  
  expect($result)->toBeInstanceOf(MissingRequiredFieldException::class);
});

it("should return a MissingRequiredField because dateTimeValidation returned an exception", function() {
  $this->emailValidation->shouldReceive('validate')->andReturn(null);
  $this->cpfValidation->shouldReceive('validate')->andReturn(null);
  $this->requiredFieldValidation->shouldReceive('validate')->andReturn(new MissingRequiredFieldException("field"));
  $this->dateTimeValidation->shouldReceive('validate')->andReturn(null);
  $this->instanceOfValidation->shouldReceive('validate')->andReturn(null);
  $this->isEnumValidation->shouldReceive('validate')->andReturn(null);
  $this->passwordValidation->shouldReceive('validate')->andReturn(null);
  $this->primitiveTypeValidation->shouldReceive('validate')->andReturn(null);
  $this->orderInputValidation->shouldReceive('validate')->andReturn(null);

  $result = $this->sut->validate(new stdClass);
  
  expect($result)->toBeInstanceOf(MissingRequiredFieldException::class);
});

it("should return a MissingRequiredField because instanceOfValidation returned an exception", function() {
  $this->emailValidation->shouldReceive('validate')->andReturn(null);
  $this->cpfValidation->shouldReceive('validate')->andReturn(null);
  $this->requiredFieldValidation->shouldReceive('validate')->andReturn(new MissingRequiredFieldException("field"));
  $this->dateTimeValidation->shouldReceive('validate')->andReturn(null);
  $this->instanceOfValidation->shouldReceive('validate')->andReturn(null);
  $this->isEnumValidation->shouldReceive('validate')->andReturn(null);
  $this->passwordValidation->shouldReceive('validate')->andReturn(null);
  $this->primitiveTypeValidation->shouldReceive('validate')->andReturn(null);
  $this->orderInputValidation->shouldReceive('validate')->andReturn(null);

  $result = $this->sut->validate(new stdClass);
  
  expect($result)->toBeInstanceOf(MissingRequiredFieldException::class);
});

it("should return a MissingRequiredField because isEnumValidation returned an exception", function() {
  $this->emailValidation->shouldReceive('validate')->andReturn(null);
  $this->cpfValidation->shouldReceive('validate')->andReturn(null);
  $this->requiredFieldValidation->shouldReceive('validate')->andReturn(new MissingRequiredFieldException("field"));
  $this->dateTimeValidation->shouldReceive('validate')->andReturn(null);
  $this->instanceOfValidation->shouldReceive('validate')->andReturn(null);
  $this->isEnumValidation->shouldReceive('validate')->andReturn(null);
  $this->passwordValidation->shouldReceive('validate')->andReturn(null);
  $this->primitiveTypeValidation->shouldReceive('validate')->andReturn(null);
  $this->orderInputValidation->shouldReceive('validate')->andReturn(null);

  $result = $this->sut->validate(new stdClass);
  
  expect($result)->toBeInstanceOf(MissingRequiredFieldException::class);
});

it("should return a MissingRequiredField because passwordValidation returned an exception", function() {
  $this->emailValidation->shouldReceive('validate')->andReturn(null);
  $this->cpfValidation->shouldReceive('validate')->andReturn(null);
  $this->requiredFieldValidation->shouldReceive('validate')->andReturn(new MissingRequiredFieldException("field"));
  $this->dateTimeValidation->shouldReceive('validate')->andReturn(null);
  $this->instanceOfValidation->shouldReceive('validate')->andReturn(null);
  $this->isEnumValidation->shouldReceive('validate')->andReturn(null);
  $this->passwordValidation->shouldReceive('validate')->andReturn(null);
  $this->primitiveTypeValidation->shouldReceive('validate')->andReturn(null);
  $this->orderInputValidation->shouldReceive('validate')->andReturn(null);

  $result = $this->sut->validate(new stdClass);
  
  expect($result)->toBeInstanceOf(MissingRequiredFieldException::class);
});

it("should return a MissingRequiredField because primitiveTypeValidation returned an exception", function() {
  $this->emailValidation->shouldReceive('validate')->andReturn(null);
  $this->cpfValidation->shouldReceive('validate')->andReturn(null);
  $this->requiredFieldValidation->shouldReceive('validate')->andReturn(new MissingRequiredFieldException("field"));
  $this->dateTimeValidation->shouldReceive('validate')->andReturn(null);
  $this->instanceOfValidation->shouldReceive('validate')->andReturn(null);
  $this->isEnumValidation->shouldReceive('validate')->andReturn(null);
  $this->passwordValidation->shouldReceive('validate')->andReturn(null);
  $this->primitiveTypeValidation->shouldReceive('validate')->andReturn(null);
  $this->orderInputValidation->shouldReceive('validate')->andReturn(null);

  $result = $this->sut->validate(new stdClass);
  
  expect($result)->toBeInstanceOf(MissingRequiredFieldException::class);
});

it("should return a InvalidOrderInputException because orderInputValidation returned an exception", function() {
  $this->emailValidation->shouldReceive('validate')->andReturn(null);
  $this->cpfValidation->shouldReceive('validate')->andReturn(null);
  $this->requiredFieldValidation->shouldReceive('validate')->andReturn(null);
  $this->dateTimeValidation->shouldReceive('validate')->andReturn(null);
  $this->instanceOfValidation->shouldReceive('validate')->andReturn(null);
  $this->isEnumValidation->shouldReceive('validate')->andReturn(null);
  $this->passwordValidation->shouldReceive('validate')->andReturn(null);
  $this->primitiveTypeValidation->shouldReceive('validate')->andReturn(null);
  $this->orderInputValidation->shouldReceive('validate')->andReturn(new InvalidOrderInputException());

  $result = $this->sut->validate(new stdClass);
  
  expect($result)->toBeInstanceOf(InvalidOrderInputException::class);
});


it("should return null because all validations returned null", function() {
  $this->emailValidation->shouldReceive('validate')->andReturn(null);
  $this->cpfValidation->shouldReceive('validate')->andReturn(null);
  $this->requiredFieldValidation->shouldReceive('validate')->andReturn(null);
  $this->dateTimeValidation->shouldReceive('validate')->andReturn(null);
  $this->instanceOfValidation->shouldReceive('validate')->andReturn(null);
  $this->isEnumValidation->shouldReceive('validate')->andReturn(null);
  $this->passwordValidation->shouldReceive('validate')->andReturn(null);
  $this->primitiveTypeValidation->shouldReceive('validate')->andReturn(null);
  $this->orderInputValidation->shouldReceive('validate')->andReturn(null);

  $result = $this->sut->validate(new stdClass);
  
  expect($result)->toBeNull();
});