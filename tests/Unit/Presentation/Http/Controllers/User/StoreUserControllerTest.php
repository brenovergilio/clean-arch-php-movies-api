<?php
use App\Core\Application\Exceptions\DuplicatedUniqueFieldException;
use App\Core\Application\Exceptions\PasswordAndConfirmationMismatchException;
use App\Core\Application\UseCases\User\CreateUser\CreateUserUseCase;
use App\Presentation\Http\Controllers\User\StoreUserController;
use App\Presentation\Http\HttpStatusCodes;
use App\Presentation\Http\HttpRequest;

beforeEach(function() {
  $this->createUserUseCaseMock = Mockery::mock(CreateUserUseCase::class);
  $this->sut = new StoreUserController($this->createUserUseCaseMock);
});

afterEach(function() {
  Mockery::close();
});

it('should return 400 status code because required field name is missing', function() {
  $body = [];
  $httpRequest = new HttpRequest($body);

  $result = $this->sut->store($httpRequest);
  expect($result->statusCode)->toBe(HttpStatusCodes::BAD_REQUEST);
  expect($result->body['error'])->toBe("Field name is missing");
});

it('should return 400 status code because required field email is missing', function() {
  $body = [
    "name" => "name"
  ];
  $httpRequest = new HttpRequest($body);

  $result = $this->sut->store($httpRequest);
  expect($result->statusCode)->toBe(HttpStatusCodes::BAD_REQUEST);
  expect($result->body['error'])->toBe("Field email is missing");
});

it('should return 400 status code because required field password is missing', function() {
  $body = [
    "name" => "name",
    "email" => "email@mail.com"
  ];
  $httpRequest = new HttpRequest($body);

  $result = $this->sut->store($httpRequest);
  expect($result->statusCode)->toBe(HttpStatusCodes::BAD_REQUEST);
  expect($result->body['error'])->toBe("Field password is missing");
});

it('should return 400 status code because required field passwordConfirmation is missing', function() {
  $body = [
    "name" => "name",
    "email" => "email@mail.com",
    "password" => "password"
  ];
  $httpRequest = new HttpRequest($body);

  $result = $this->sut->store($httpRequest);
  expect($result->statusCode)->toBe(HttpStatusCodes::BAD_REQUEST);
  expect($result->body['error'])->toBe("Field passwordConfirmation is missing");
});

it('should return 400 status code because email is invalid', function() {
  $body = [
    "name" => "name",
    "email" => "email",
    "password" => "password",
    "passwordConfirmation" => "password" 
  ];
  $httpRequest = new HttpRequest($body);

  $result = $this->sut->store($httpRequest);
  expect($result->statusCode)->toBe(HttpStatusCodes::BAD_REQUEST);
  expect($result->body['error'])->toBe("Field email is invalid");
});

it('should return 400 status code because CPF is invalid', function() {
  $body = [
    "name" => "name",
    "email" => "email@mail.com",
    "password" => "password",
    "passwordConfirmation" => "password",
    "cpf" => "invalidCpf"
  ];
  $httpRequest = new HttpRequest($body);

  $result = $this->sut->store($httpRequest);
  expect($result->statusCode)->toBe(HttpStatusCodes::BAD_REQUEST);
  expect($result->body['error'])->toBe("Field cpf is invalid");
});

it('should return 400 status code because password is weak', function() {
  $body = [
    "name" => "name",
    "email" => "email@mail.com",
    "password" => "password",
    "passwordConfirmation" => "password",
  ];
  $httpRequest = new HttpRequest($body);

  $result = $this->sut->store($httpRequest);
  expect($result->statusCode)->toBe(HttpStatusCodes::BAD_REQUEST);
  expect($result->body['error'])->toBe("The password must contain at least one uppercase letter, one lowercase letter, one special character, and one number");
});

it('should return 400 status code because use case thrown PasswordAndConfirmationMismatchException', function() {
  $body = [
    "name" => "name",
    "email" => "email@mail.com",
    "password" => "SenhaForte@123.",
    "passwordConfirmation" => "password",
  ];
  $this->createUserUseCaseMock->shouldReceive('execute')->andThrow(new PasswordAndConfirmationMismatchException);
  $httpRequest = new HttpRequest($body);

  $result = $this->sut->store($httpRequest);
  expect($result->statusCode)->toBe(HttpStatusCodes::BAD_REQUEST);
  expect($result->body['error'])->toBe("Password and confirmation are not equal");
});

it('should return 409 status code because use case thrown DuplicatedUniqueFieldException', function() {
  $body = [
    "name" => "name",
    "email" => "email@mail.com",
    "password" => "SenhaForte@123.",
    "passwordConfirmation" => "SenhaForte@123.",
  ];
  $this->createUserUseCaseMock->shouldReceive('execute')->andThrow(new DuplicatedUniqueFieldException("field"));
  $httpRequest = new HttpRequest($body);

  $result = $this->sut->store($httpRequest);
  expect($result->statusCode)->toBe(HttpStatusCodes::CONFLICT);
  expect($result->body['error'])->toBe("Field field is already in use");
});

it('should return 201 in case of success', function() {
  $body = [
    "name" => "name",
    "email" => "email@mail.com",
    "password" => "SenhaForte@123.",
    "passwordConfirmation" => "SenhaForte@123.",
  ];
  $httpRequest = new HttpRequest($body);
  $this->createUserUseCaseMock->shouldReceive('execute')->once();
  
  $result = $this->sut->store($httpRequest);
  expect($result->statusCode)->toBe(HttpStatusCodes::CREATED);
  expect($result->body)->toBeNull();
});