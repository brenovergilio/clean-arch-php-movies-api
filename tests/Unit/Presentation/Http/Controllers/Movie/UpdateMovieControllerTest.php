<?php
use App\Core\Application\Exceptions\InsufficientPermissionsException;
use App\Core\Application\UseCases\Movie\UpdateMovie\UpdateMovieUseCase;
use App\Core\Domain\Exceptions\EntityNotFoundException;
use App\Presentation\Http\Controllers\Movie\UpdateMovieController;
use App\Presentation\Http\HttpStatusCodes;
use App\Presentation\Http\HttpRequest;

beforeEach(function() {
  $this->updateMovieUseCaseMock = Mockery::mock(UpdateMovieUseCase::class);
  $this->sut = new UpdateMovieController($this->updateMovieUseCaseMock);
});

afterEach(function() {
  Mockery::close();
});

it('should return 404 status code because use case thrown EntityNotFoundException', function() {
  $body = [];
  $httpRequest = new HttpRequest($body);

  $this->updateMovieUseCaseMock->shouldReceive('execute')->andThrow(new EntityNotFoundException("Entity"));

  $result = $this->sut->update('id', $httpRequest);
  expect($result->statusCode)->toBe(HttpStatusCodes::NOT_FOUND);
  expect($result->body['error'])->toBe("Entity Entity not found");
});

it('should return 403 status code because use case thrown InsufficientPermissionsException', function() {
  $body = [];
  $httpRequest = new HttpRequest($body);

  $this->updateMovieUseCaseMock->shouldReceive('execute')->andThrow(new InsufficientPermissionsException());

  $result = $this->sut->update('id', $httpRequest);
  expect($result->statusCode)->toBe(HttpStatusCodes::FORBIDDEN);
  expect($result->body['error'])->toBe("Insufficient Permissions");
});

it('should return 204 status code in case of success', function() {
  $body = [];
  $this->updateMovieUseCaseMock->shouldReceive('execute')->once();

  $httpRequest = new HttpRequest($body);

  $result = $this->sut->update('id', $httpRequest);
  expect($result->statusCode)->toBe(HttpStatusCodes::NO_CONTENT);
  expect($result->body)->toBeNull();
});