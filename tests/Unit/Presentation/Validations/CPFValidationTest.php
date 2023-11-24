<?php
use App\Core\Domain\Exceptions\InvalidFieldException;
use App\Presentation\Validations\CPFValidation;
use App\Presentation\Validations\Interfaces\CPFValidator;

beforeEach(function() {
  $this->fieldName = "cpf";
  $this->cpfValidatorMock = Mockery::mock(CPFValidator::class);
  $this->sut = new CPFValidation($this->fieldName, $this->cpfValidatorMock);
});

afterEach(function() {
  Mockery::close();
});

it('should return an InvalidFieldException because cpfValidator returned false', function() {
  $this->cpfValidatorMock->shouldReceive('isValid')->andReturn(false);

  $input = ["cpf" => "someCPF"];
  $result = $this->sut->validate($input);
  
  expect($result)->toBeInstanceOf(InvalidFieldException::class);
});

it('should return null because cpfValidator returned true', function() {
  $this->cpfValidatorMock->shouldReceive('isValid')->andReturn(true);

  $input = ["cpf" => "someCPF"];
  $result = $this->sut->validate($input);
  
  expect($result)->toBeNull();
});
