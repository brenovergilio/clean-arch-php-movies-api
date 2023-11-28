<?php
use App\Core\Application\UseCases\Movie\FindMovie\DTO\FindMovieInputDTO;
use App\Core\Application\UseCases\Movie\FindMovie\FindMovieUseCase;
use App\Core\Domain\Entities\Movie\MovieRepository;
use App\Models\MovieModel;
use App\Models\UserModel;

beforeEach(function() {
  $this->movieRepositoryMock = Mockery::mock(MovieRepository::class);
  $this->inputDto = new FindMovieInputDTO('id');
  $this->loggedUser = UserModel::factory()->client()->makeOne()->mapToDomain();
  $this->sut = new FindMovieUseCase($this->movieRepositoryMock, $this->loggedUser);
});

afterEach(function() {
  Mockery::close();
});


it("should throw an EntityNotFoundException because movie does not exist", function() {
  $this->movieRepositoryMock->shouldReceive('findByID')->andReturn(null);

  expect(function() {
    $this->sut->execute($this->inputDto);
  })->toThrow("Entity Movie not found");
});

it("should throw an EntityNotFoundException because movie is private and user did not confirm email", function() {
  $movie = MovieModel::factory()->private()->makeOne()->mapToDomain();
  $this->loggedUser = UserModel::factory()->client()->unverified()->makeOne()->mapToDomain();
  $this->sut = new FindMovieUseCase($this->movieRepositoryMock, $this->loggedUser);

  $this->movieRepositoryMock->shouldReceive('findByID')->andReturn($movie);

  expect(function() {
    $this->sut->execute($this->inputDto);
  })->toThrow("Entity Movie not found");
});

it("should return the movie", function() {
  $appUrl = env("APP_URL", "");
  $movie = MovieModel::factory()->makeOne([
    "id" => 1
  ])->mapToDomain();
  $this->movieRepositoryMock->shouldReceive('findByID')->andReturn($movie);

  $this->inputDto->id = $movie->id();
  $result = $this->sut->execute($this->inputDto);
  
  expect($result->id)->toBe($movie->id());
  expect($result->title)->toBe($movie->title());
  expect($result->synopsis)->toBe($movie->synopsis());
  expect($result->directorName)->toBe($movie->directorName());
  expect($result->genre)->toBe($movie->genre());
  expect($result->cover)->toBe("$appUrl/api/movies/1/cover");
  expect($result->isPublic)->toBe($movie->isPublic());
  expect($result->releaseDate)->toBe($movie->releaseDate());
  expect($result->addedAt)->toBe($movie->addedAt());
});