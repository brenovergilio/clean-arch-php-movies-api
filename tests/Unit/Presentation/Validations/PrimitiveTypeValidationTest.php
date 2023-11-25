<?php
use App\Core\Domain\Exceptions\InvalidFieldException;
use App\Presentation\Validations\PrimitiveTypeValidation;

it('should return an InvalidFieldException because input is not a string', function() {
  $sut = new PrimitiveTypeValidation("field", "string");
  $input = ["field" => 123];
  $result = $sut->validate($input);
  
  expect($result)->toBeInstanceOf(InvalidFieldException::class);
});

it('should return an InvalidFieldException because input is not a int', function() {
  $sut = new PrimitiveTypeValidation("field", "int");
  $input = ["field" => "123"];
  $result = $sut->validate($input);
  
  expect($result)->toBeInstanceOf(InvalidFieldException::class);
});

it('should return an InvalidFieldException because input is not a float', function() {
  $sut = new PrimitiveTypeValidation("field", "float");
  $input = ["field" => "123"];
  $result = $sut->validate($input);
  
  expect($result)->toBeInstanceOf(InvalidFieldException::class);
});

it('should return an InvalidFieldException because input is not a bool', function() {
  $sut = new PrimitiveTypeValidation("field", "bool");
  $input = ["field" => "123"];
  $result = $sut->validate($input);
  
  expect($result)->toBeInstanceOf(InvalidFieldException::class);
});

it('should return null because input is string', function() {
  $sut = new PrimitiveTypeValidation("field", "string");
  $input = ["field" => "123"];
  $result = $sut->validate($input);
  
  expect($result)->toBeNull();
});

it('should return null because input is int', function() {
  $sut = new PrimitiveTypeValidation("field", "int");
  $input = ["field" => 123];
  $result = $sut->validate($input);
  
  expect($result)->toBeNull();
});

it('should return null because input is float', function() {
  $sut = new PrimitiveTypeValidation("field", "float");
  $input = ["field" => 123.76];
  $result = $sut->validate($input);
  
  expect($result)->toBeNull();
});

it('should return null because input is bool', function() {
  $sut = new PrimitiveTypeValidation("field", "bool");
  $input = ["field" => true];
  $result = $sut->validate($input);
  
  expect($result)->toBeNull();
});

it('should return null because there is no primitive type with the provided name', function() {
  $sut = new PrimitiveTypeValidation("field", "invalid");
  $input = ["field" => true];
  $result = $sut->validate($input);
  
  expect($result)->toBeNull();
});