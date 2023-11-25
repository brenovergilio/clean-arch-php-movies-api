<?php
use App\Core\Application\UseCases\User\ConfirmEmail\ConfirmEmailUseCase;
use App\Core\Domain\Exceptions\EntityNotFoundException;
use App\Presentation\Http\Controllers\User\ConfirmEmailUserController;
use App\Presentation\Http\HttpStatusCodes;
use App\Presentation\Http\HttpRequest;

beforeEach(function() {
  $this->confirmEmailUseCaseMock = Mockery::mock(ConfirmEmailUseCase::class);
  $this->sut = new ConfirmEmailUserController($this->confirmEmailUseCaseMock);
});

afterEach(function() {
  Mockery::close();
});

it('should return 400 status code because required field accessToken is missing', function() {
  $body = [];
  $httpRequest = new HttpRequest($body);

  $result = $this->sut->confirmEmail($httpRequest);
  expect($result->statusCode)->toBe(HttpStatusCodes::BAD_REQUEST);
  expect($result->body['error'])->toBe("Field accessToken is missing");
});

it('should return 400 status code because accessToken is not a string', function() {
  $body = [
    "accessToken" =>  123
  ];
  $httpRequest = new HttpRequest($body);

  $result = $this->sut->confirmEmail($httpRequest);
  expect($result->statusCode)->toBe(HttpStatusCodes::BAD_REQUEST);
  expect($result->body['error'])->toBe("Field accessToken is invalid");
});


it('should return 404 status code because use case thrown EntityNotFoundException', function() {
  $body = [
    "accessToken" => "A1B2C3"
  ];
  $httpRequest = new HttpRequest($body);

  $this->confirmEmailUseCaseMock->shouldReceive('execute')->andThrow(new EntityNotFoundException("Entity"));

  $result = $this->sut->confirmEmail($httpRequest);
  expect($result->statusCode)->toBe(HttpStatusCodes::NOT_FOUND);
  expect($result->body['error'])->toBe("Entity Entity not found");
});

it('should return 204 status code in case of success', function() {
  $body = [
    "accessToken" => "A1B2C3"
  ];

  $this->confirmEmailUseCaseMock->shouldReceive('execute')->once();
  $httpRequest = new HttpRequest($body);

  $result = $this->sut->confirmEmail($httpRequest);
  expect($result->statusCode)->toBe(HttpStatusCodes::NO_CONTENT);
  expect($result->body)->toBeNull();
});