<?php
use App\Core\Application\Exceptions\InvalidCredentialsException;
use App\Core\Application\UseCases\Auth\Login\DTO\LoginOutputDTO;
use App\Core\Application\UseCases\Auth\Login\LoginUseCase;
use App\Presentation\Http\Controllers\Auth\LoginController;
use App\Presentation\Http\HttpRequest;
use App\Presentation\Http\HttpStatusCodes;

beforeEach(function() {
  $this->loginUseCaseMock = Mockery::mock(LoginUseCase::class);
  $this->sut = new LoginController($this->loginUseCaseMock);
});

afterEach(function() {
  Mockery::close();
});

it('should return 400 status code because required field email is missing', function () {
  $body = [];
  $httpRequest = new HttpRequest($body);

  $result = $this->sut->login($httpRequest);
  expect($result->statusCode)->toBe(HttpStatusCodes::BAD_REQUEST);
  expect($result->body["error"])->toBe("Field email is missing");
});

it('should return 400 status code because required field password is missing', function () {
  $body = [
    "email" => "email@mail.com"
  ];

  $httpRequest = new HttpRequest($body);

  $result = $this->sut->login($httpRequest);
  expect($result->statusCode)->toBe(HttpStatusCodes::BAD_REQUEST);
  expect($result->body["error"])->toBe("Field password is missing");
});

it('should return 400 status code because email is not valid', function () {
  $body = [
    "email" => "email",
    "password" => "password"
  ];

  $httpRequest = new HttpRequest($body);

  $result = $this->sut->login($httpRequest);
  expect($result->statusCode)->toBe(HttpStatusCodes::BAD_REQUEST);
  expect($result->body["error"])->toBe("Field email is invalid");
});

it('should return 400 status code because email is not a string', function () {
  $body = [
    "email" => "email@mail.com",
    "password" => 123
  ];

  $httpRequest = new HttpRequest($body);

  $result = $this->sut->login($httpRequest);
  expect($result->statusCode)->toBe(HttpStatusCodes::BAD_REQUEST);
  expect($result->body["error"])->toBe("Field password is invalid");
});

it('should return 401 status code because use case thrown InvalidCredentialsException', function () {
  $body = [
    "email" => "email@mail.com",
    "password" => "password"
  ];

  $this->loginUseCaseMock->shouldReceive('execute')->andThrow(new InvalidCredentialsException());

  $httpRequest = new HttpRequest($body);

  $result = $this->sut->login($httpRequest);
  expect($result->statusCode)->toBe(HttpStatusCodes::UNAUTHORIZED);
  expect($result->body["error"])->toBe("Invalid Credentials");
});

it('should return 201 status code on success', function () {
  $body = [
    "email" => "email@mail.com",
    "password" => "password"
  ];

  $outputDto = new LoginOutputDTO(
    'id',
    'token',
    true
  );

  $this->loginUseCaseMock->shouldReceive('execute')->once()->andReturn($outputDto);

  $httpRequest = new HttpRequest($body);

  $result = $this->sut->login($httpRequest);
  expect($result->statusCode)->toBe(HttpStatusCodes::CREATED);
  expect($result->body["data"])->toBe($outputDto);
});