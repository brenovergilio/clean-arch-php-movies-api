<?php
use App\Core\Application\UseCases\Movie\DeleteMovie\DeleteMovieUseCase;
use App\Core\Application\UseCases\Movie\FindMovie\DTO\FindMovieOutputDTO;
use App\Core\Application\UseCases\Movie\FindMovie\FindMovieUseCase;
use App\Core\Domain\Entities\Movie\MovieGenre;
use App\Core\Domain\Exceptions\EntityNotFoundException;
use App\Presentation\Http\Controllers\Movie\DeleteMovieController;
use App\Presentation\Http\Controllers\Movie\FindMovieController;
use App\Presentation\Http\HttpStatusCodes;

beforeEach(function() {
  $this->deleteMovieUseCaseMock = Mockery::mock(DeleteMovieUseCase::class);
  $this->sut = new DeleteMovieController($this->deleteMovieUseCaseMock);
});

afterEach(function() {
  Mockery::close();
});

it('should return 404 status code because use case thrown EntityNotFoundException', function() {
  $this->deleteMovieUseCaseMock->shouldReceive('execute')->andThrow(new EntityNotFoundException("Entity"));

  $result = $this->sut->delete('id');
  expect($result->statusCode)->toBe(HttpStatusCodes::NOT_FOUND);
  expect($result->body['error'])->toBe("Entity Entity not found");
});

it('should return 200 status code in case of success', function() {
  $this->deleteMovieUseCaseMock->shouldReceive('execute')->once();

  $result = $this->sut->delete('id');
  expect($result->statusCode)->toBe(HttpStatusCodes::NO_CONTENT);
  expect($result->body)->toBeNull();
});