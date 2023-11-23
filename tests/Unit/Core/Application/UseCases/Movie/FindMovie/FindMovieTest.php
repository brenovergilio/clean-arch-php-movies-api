<?php
use App\Core\Application\UseCases\Movie\FindMovie\DTO\FindMovieInputDTO;
use App\Core\Application\UseCases\Movie\FindMovie\FindMovieUseCase;
use App\Core\Domain\Entities\Movie\Movie;
use App\Core\Domain\Entities\Movie\MovieGenre;
use App\Core\Domain\Entities\Movie\MovieRepository;
use DateTime;

beforeEach(function() {
  $this->movieRepositoryMock = Mockery::mock(MovieRepository::class);
  $this->inputDto = new FindMovieInputDTO('id');
  $this->sut = new FindMovieUseCase($this->movieRepositoryMock);
});

it("should throw an EntityNotFoundException because movie does not exist", function() {
  $this->movieRepositoryMock->shouldReceive('findByID')->andReturn(null);

  expect(function() {
    $this->sut->execute($this->inputDto);
  })->toThrow("Entity Movie not found");
});

it("should return the movie", function() {
  $movie = new Movie(
    'id',
    'title',
    'synopsis',
    'directorName',
    MovieGenre::ACTION,
    null,
    true,
    new DateTime(),
    new DateTime()
  );
  $this->movieRepositoryMock->shouldReceive('findByID')->andReturn($movie);

  $result = $this->sut->execute($this->inputDto);
  
  expect($result->id)->toBe($movie->id());
  expect($result->title)->toBe($movie->title());
  expect($result->synopsis)->toBe($movie->synopsis());
  expect($result->directorName)->toBe($movie->directorName());
  expect($result->genre)->toBe($movie->genre());
  expect($result->cover)->toBe($movie->cover());
  expect($result->isPublic)->toBe($movie->isPublic());
  expect($result->releaseDate)->toBe($movie->releaseDate());
  expect($result->addedAt)->toBe($movie->addedAt());
});