<?php
use App\Core\Application\Exceptions\InsufficientPermissionsException;
use App\Core\Application\UseCases\User\UpdateUser\UpdateUserUseCase;
use App\Presentation\Http\Controllers\User\UpdateUserController;
use App\Core\Application\Exceptions\DuplicatedUniqueFieldException;
use App\Core\Domain\Exceptions\EntityNotFoundException;
use App\Presentation\Http\HttpStatusCodes;
use App\Presentation\Http\HttpRequest;

beforeEach(function() {
  $this->updateUserUseCaseMock = Mockery::mock(UpdateUserUseCase::class);
  $this->sut = new UpdateUserController($this->updateUserUseCaseMock);
});

afterEach(function() {
  Mockery::close();
});

it('should return 400 status code because CPF is invalid', function() {
  $body = [
    "cpf" => "invalidCpf"
  ];
  $httpRequest = new HttpRequest($body);

  $result = $this->sut->update($httpRequest);
  expect($result->statusCode)->toBe(HttpStatusCodes::BAD_REQUEST);
  expect($result->body['error'])->toBe("Field cpf is invalid");
});

it('should return 400 status code because email is invalid', function() {
  $body = [
    "cpf" => "14629037039",
    "email" => "email"
  ];
  $httpRequest = new HttpRequest($body);

  $result = $this->sut->update($httpRequest);
  expect($result->statusCode)->toBe(HttpStatusCodes::BAD_REQUEST);
  expect($result->body['error'])->toBe("Field email is invalid");
});

it('should return 400 status code because name is not a string', function() {
  $body = [
    "name" => 123
  ];
  $httpRequest = new HttpRequest($body);

  $result = $this->sut->update($httpRequest);
  expect($result->statusCode)->toBe(HttpStatusCodes::BAD_REQUEST);
  expect($result->body['error'])->toBe("Field name is invalid");
});

it('should return 400 status code because photo is not an instance of UploadableFile', function() {
  $body = [
    "photo" => "invalidPhoto"
  ];
  $httpRequest = new HttpRequest($body);

  $result = $this->sut->update($httpRequest);
  expect($result->statusCode)->toBe(HttpStatusCodes::BAD_REQUEST);
  expect($result->body['error'])->toBe("Field photo is invalid");
});

it('should return 409 status code because use case thrown DuplicatedUniqueFieldException', function() {
  $body = [];
  $this->updateUserUseCaseMock->shouldReceive('execute')->andThrow(new DuplicatedUniqueFieldException("field"));
  $httpRequest = new HttpRequest($body);

  $result = $this->sut->update($httpRequest);
  expect($result->statusCode)->toBe(HttpStatusCodes::CONFLICT);
  expect($result->body['error'])->toBe("Field field is already in use");
});

it('should return 404 status code because use case thrown EntityNotFoundException', function() {
  $body = [];
  $this->updateUserUseCaseMock->shouldReceive('execute')->andThrow(new EntityNotFoundException("Entity"));
  $httpRequest = new HttpRequest($body);

  $result = $this->sut->update($httpRequest);
  expect($result->statusCode)->toBe(HttpStatusCodes::NOT_FOUND);
  expect($result->body['error'])->toBe("Entity Entity not found");
});

it('should return 403 status code because use case thrown InsufficientPermissionsException', function() {
  $body = [];
  $this->updateUserUseCaseMock->shouldReceive('execute')->andThrow(new InsufficientPermissionsException());
  $httpRequest = new HttpRequest($body);

  $result = $this->sut->update($httpRequest);
  expect($result->statusCode)->toBe(HttpStatusCodes::FORBIDDEN);
  expect($result->body['error'])->toBe("Insufficient Permissions");
});

it('should return 204 status code in case of success', function() {
  $body = [];
  $this->updateUserUseCaseMock->shouldReceive('execute')->once();
  $httpRequest = new HttpRequest($body);

  $result = $this->sut->update($httpRequest);
  expect($result->statusCode)->toBe(HttpStatusCodes::NO_CONTENT);
  expect($result->body)->toBeNull();
});