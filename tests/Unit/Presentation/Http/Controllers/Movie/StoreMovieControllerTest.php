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

it('should return 400 status code because title is not a string', function() {
  $body = [
    "title" => 123,
    "synopsis" => "synopsis",
    "directorName" => "directorName",
    "genre" => "comedy",
    "isPublic" => true,
    "releaseDate" => "2000-01-01",
  ];
  $httpRequest = new HttpRequest($body);

  $result = $this->sut->store($httpRequest);
  expect($result->statusCode)->toBe(HttpStatusCodes::BAD_REQUEST);
  expect($result->body['error'])->toBe("Field title is invalid");
});

it('should return 400 status code because synopsis is not a string', function() {
  $body = [
    "title" => "title",
    "synopsis" => 123,
    "directorName" => "directorName",
    "genre" => "comedy",
    "isPublic" => true,
    "releaseDate" => "2000-01-01",
  ];
  $httpRequest = new HttpRequest($body);

  $result = $this->sut->store($httpRequest);
  expect($result->statusCode)->toBe(HttpStatusCodes::BAD_REQUEST);
  expect($result->body['error'])->toBe("Field synopsis is invalid");
});

it('should return 400 status code because directorName is not a string', function() {
  $body = [
    "title" => "title",
    "synopsis" => "synopsis",
    "directorName" => 123,
    "genre" => "comedy",
    "isPublic" => true,
    "releaseDate" => "2000-01-01",
  ];
  $httpRequest = new HttpRequest($body);

  $result = $this->sut->store($httpRequest);
  expect($result->statusCode)->toBe(HttpStatusCodes::BAD_REQUEST);
  expect($result->body['error'])->toBe("Field directorName is invalid");
});

it('should return 400 status code because genre is not a valid enum', function() {
  $body = [
    "title" => "title",
    "synopsis" => "synopsis",
    "directorName" => "directorName",
    "genre" => "invalidGenre",
    "isPublic" => true,
    "releaseDate" => "2000-01-01",
  ];
  $httpRequest = new HttpRequest($body);

  $result = $this->sut->store($httpRequest);
  expect($result->statusCode)->toBe(HttpStatusCodes::BAD_REQUEST);
  expect($result->body['error'])->toBe("Field genre is invalid");
});

it('should return 400 status code because isPublic is not a bool', function() {
  $body = [
    "title" => "title",
    "synopsis" => "synopsis",
    "directorName" => "directorName",
    "genre" => "comedy",
    "isPublic" => "invalidBool",
    "releaseDate" => "2000-01-01",
  ];
  $httpRequest = new HttpRequest($body);

  $result = $this->sut->store($httpRequest);
  expect($result->statusCode)->toBe(HttpStatusCodes::BAD_REQUEST);
  expect($result->body['error'])->toBe("Field isPublic is invalid");
});

it('should return 400 status code because releaseDate is not a valid dateTime', function() {
  $body = [
    "title" => "title",
    "synopsis" => "synopsis",
    "directorName" => "directorName",
    "genre" => "comedy",
    "isPublic" => true,
    "releaseDate" => "invalidDateTime"
  ];
  $httpRequest = new HttpRequest($body);

  $result = $this->sut->store($httpRequest);
  expect($result->statusCode)->toBe(HttpStatusCodes::BAD_REQUEST);
  expect($result->body['error'])->toBe("Field releaseDate is invalid");
});

it('should return 400 status code because cover is not a instance of UploadableFile', function() {
  $body = [
    "title" => "title",
    "synopsis" => "synopsis",
    "directorName" => "directorName",
    "genre" => "comedy",
    "isPublic" => true,
    "releaseDate" => "2000-01-01",
    "cover" => "invalid"
  ];
  $httpRequest = new HttpRequest($body);

  $result = $this->sut->store($httpRequest);
  expect($result->statusCode)->toBe(HttpStatusCodes::BAD_REQUEST);
  expect($result->body['error'])->toBe("Field cover is invalid");
});

it('should return 403 status code because use case thrown InsufficientPermissionsException', function() {
  $body = [
    "title" => "title",
    "synopsis" => "synopsis",
    "directorName" => "directorName",
    "genre" => "comedy",
    "isPublic" => true,
    "releaseDate" => "2000-01-01"
  ];
  $httpRequest = new HttpRequest($body);

  $this->createMovieUseCaseMock->shouldReceive('execute')->andThrow(new InsufficientPermissionsException());

  $result = $this->sut->store($httpRequest);
  expect($result->statusCode)->toBe(HttpStatusCodes::FORBIDDEN);
  expect($result->body['error'])->toBe("Insufficient Permissions");
});

it('should return 201 status code in case of success', function() {
  $body = [
    "title" => "title",
    "synopsis" => "synopsis",
    "directorName" => "directorName",
    "genre" => "comedy",
    "isPublic" => true,
    "releaseDate" => "2000-01-01"
  ];
  
  $this->createMovieUseCaseMock->shouldReceive('execute')->once();

  $httpRequest = new HttpRequest($body);

  $result = $this->sut->store($httpRequest);
  expect($result->statusCode)->toBe(HttpStatusCodes::CREATED);
  expect($result->body)->toBeNull();
});