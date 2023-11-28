<?php
use App\Core\Application\UseCases\Movie\FindManyMovies\DTO\FindManyMoviesInputDTO;
use App\Core\Application\UseCases\Movie\FindManyMovies\FindManyMoviesUseCase;
use App\Core\Domain\Entities\Movie\FilterMovies;
use App\Core\Domain\Entities\Movie\Movie;
use App\Core\Domain\Entities\Movie\MovieGenre;
use App\Core\Domain\Entities\Movie\MovieRepository;
use App\Core\Domain\Entities\Movie\OrderMovies;
use App\Core\Domain\Protocols\PaginatedResult;
use App\Core\Domain\Protocols\PaginationProps;
use App\Models\MovieModel;
use App\Models\UserModel;

beforeEach(function() {
  $this->movieRepositoryMock = Mockery::mock(MovieRepository::class);
  $this->loggedUser = UserModel::factory()->client()->makeOne()->mapToDomain();
  $this->inputDto = new FindManyMoviesInputDTO(
    new PaginationProps(1, 12),
    new FilterMovies(null, null),
    new OrderMovies([])
  );
  $this->sut = new FindManyMoviesUseCase($this->movieRepositoryMock, $this->loggedUser);
});

afterEach(function() {
  Mockery::close();
});

it("should call findMany with isPublic filtering prop set to true when user did not confirm email, even if other value is provided", function() {
  $this->loggedUser = UserModel::factory()->client()->unverified()->makeOne()->mapToDomain();
  $this->inputDto->filterMoviesProps->isPublic = false;
  $repositoryResult = new PaginatedResult([], $this->inputDto->paginationProps);

  $this->movieRepositoryMock->shouldReceive('findMany')->with($this->inputDto->paginationProps, Mockery::on(function ($argument) {
    return $argument->isPublic === true;
  }), $this->inputDto->orderMoviesProps)->andReturn($repositoryResult)->once();

  $this->sut = new FindManyMoviesUseCase($this->movieRepositoryMock, $this->loggedUser);
  $this->sut->execute($this->inputDto);
});

it("should return same objects returned by repository", function() {
  $firstMovie = MovieModel::factory()->makeOne()->mapToDomain();
  $secondMovie = MovieModel::factory()->makeOne()->mapToDomain();

  $movies = [
    $firstMovie,
    $secondMovie
  ];

  $repositoryResult = new PaginatedResult($movies, $this->inputDto->paginationProps);
  $this->movieRepositoryMock->shouldReceive('findMany')->andReturn($repositoryResult);

  $result = $this->sut->execute($this->inputDto);

  expect(count($result->data))->toBe(2);
  expect($result->paginationProps->page)->toBe(1);
  expect($result->paginationProps->perPage)->toBe(12);
  
  expect($result->data[0]->id)->toBe($firstMovie->id());
  expect($result->data[0]->title)->toBe($firstMovie->title());
  expect($result->data[0]->synopsis)->toBe($firstMovie->synopsis());
  expect($result->data[0]->directorName)->toBe($firstMovie->directorName());
  expect($result->data[0]->isPublic)->toBe($firstMovie->isPublic());
  expect($result->data[0]->genre)->toBe($firstMovie->genre());

  expect($result->data[1]->id)->toBe($secondMovie->id());
  expect($result->data[1]->title)->toBe($secondMovie->title());
  expect($result->data[1]->synopsis)->toBe($secondMovie->synopsis());
  expect($result->data[1]->directorName)->toBe($secondMovie->directorName());
  expect($result->data[1]->isPublic)->toBe($secondMovie->isPublic());
  expect($result->data[1]->genre)->toBe($secondMovie->genre());
});

it("should return url when movie has cover", function() {
  $movie = MovieModel::factory()->makeOne()->mapToDomain();

  $movies = [
    $movie
  ];

  $repositoryResult = new PaginatedResult($movies, $this->inputDto->paginationProps);
  $this->movieRepositoryMock->shouldReceive('findMany')->andReturn($repositoryResult);

  $result = $this->sut->execute($this->inputDto);
  expect($result->data[0]->cover)->toBeUrl();
});

it("should not return cover property if movie does not have one", function() {
  $movie = MovieModel::factory()->makeOne([
    "cover" => null
  ])->mapToDomain();

  $movies = [
    $movie
  ];

  $repositoryResult = new PaginatedResult($movies, $this->inputDto->paginationProps);
  $this->movieRepositoryMock->shouldReceive('findMany')->andReturn($repositoryResult);

  $result = $this->sut->execute($this->inputDto);
  expect($result->data[0])->not->toHaveProperty('cover');
});