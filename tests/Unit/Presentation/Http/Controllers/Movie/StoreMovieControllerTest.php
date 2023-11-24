<?php
use App\Core\Application\Exceptions\InsufficientPermissionsException;
use App\Core\Application\UseCases\Movie\CreateMovie\CreateMovieUseCase;
use App\Presentation\Http\Controllers\Movie\StoreMovieController;
use App\Presentation\Http\HttpStatusCodes;
use App\Presentation\Http\HttpRequest;

beforeEach(function() {
  $this->createMovieUseCaseMock = Mockery::mock(CreateMovieUseCase::class);
  $this->sut = new StoreMovieController($this->createMovieUseCaseMock);
});

afterEach(function() {
  Mockery::close();
});

it('should return 400 status code because required field title is missing', function() {
  $body = [];
  $httpRequest = new HttpRequest($body);

  $result = $this->sut->store($httpRequest);
  expect($result->statusCode)->toBe(HttpStatusCodes::BAD_REQUEST);
  expect($result->body['error'])->toBe("Field title is missing");
});

it('should return 400 status code because required field synopsis is missing', function() {
  $body = [
    "title" => "title"
  ];
  $httpRequest = new HttpRequest($body);

  $result = $this->sut->store($httpRequest);
  expect($result->statusCode)->toBe(HttpStatusCodes::BAD_REQUEST);
  expect($result->body['error'])->toBe("Field synopsis is missing");
});

it('should return 400 status code because required field directorName is missing', function() {
  $body = [
    "title" => "title",
    "synopsis" => "synopsis"
  ];
  $httpRequest = new HttpRequest($body);

  $result = $this->sut->store($httpRequest);
  expect($result->statusCode)->toBe(HttpStatusCodes::BAD_REQUEST);
  expect($result->body['error'])->toBe("Field directorName is missing");
});

it('should return 400 status code because required field genre is missing', function() {
  $body = [
    "title" => "title",
    "synopsis" => "synopsis",
    "directorName" => "directorName"
  ];
  $httpRequest = new HttpRequest($body);

  $result = $this->sut->store($httpRequest);
  expect($result->statusCode)->toBe(HttpStatusCodes::BAD_REQUEST);
  expect($result->body['error'])->toBe("Field genre is missing");
});

it('should return 400 status code because required field isPublic is missing', function() {
  $body = [
    "title" => "title",
    "synopsis" => "synopsis",
    "directorName" => "directorName",
    "genre" => "comedy"
  ];
  $httpRequest = new HttpRequest($body);

  $result = $this->sut->store($httpRequest);
  expect($result->statusCode)->toBe(HttpStatusCodes::BAD_REQUEST);
  expect($result->body['error'])->toBe("Field isPublic is missing");
});

it('should return 400 status code because required field releaseDate is missing', function() {
  $body = [
    "title" => "title",
    "synopsis" => "synopsis",
    "directorName" => "directorName",
    "genre" => "comedy",
    "isPublic" => true
  ];
  $httpRequest = new HttpRequest($body);

  $result = $this->sut->store($httpRequest);
  expect($result->statusCode)->toBe(HttpStatusCodes::BAD_REQUEST);
  expect($result->body['error'])->toBe("Field releaseDate is missing");
});

it('should return 403 status code because use case thrown InsufficientPermissionsException', function() {
  $body = [
    "title" => "title",
    "synopsis" => "synopsis",
    "directorName" => "directorName",
    "genre" => "comedy",
    "isPublic" => true,
    "releaseDate" => new DateTime()
  ];
  $httpRequest = new HttpRequest($body);

  $this->createMovieUseCaseMock->shouldReceive('execute')->andThrow(new InsufficientPermissionsException());

  $result = $this->sut->store($httpRequest);
  expect($result->statusCode)->toBe(HttpStatusCodes::FORBIDDEN);
  expect($result->body['error'])->toBe("Insufficient Permissions");
});

it('should return 204 status code in case of success', function() {
  $body = [
    "title" => "title",
    "synopsis" => "synopsis",
    "directorName" => "directorName",
    "genre" => "comedy",
    "isPublic" => true,
    "releaseDate" => new DateTime()
  ];
  
  $this->createMovieUseCaseMock->shouldReceive('execute')->once();

  $httpRequest = new HttpRequest($body);

  $result = $this->sut->store($httpRequest);
  expect($result->statusCode)->toBe(HttpStatusCodes::NO_CONTENT);
  expect($result->body)->toBeNull();
});