<?php
use App\Core\Application\UseCases\Movie\FindManyMovies\DTO\FindManyMoviesOutputDTO;
use App\Core\Application\UseCases\Movie\FindManyMovies\FindManyMoviesUseCase;
use App\Core\Application\UseCases\Movie\FindMovie\DTO\FindMovieOutputDTO;
use App\Core\Application\UseCases\Movie\FindMovie\FindMovieUseCase;
use App\Core\Domain\Entities\Movie\MovieGenre;
use App\Core\Domain\Exceptions\EntityNotFoundException;
use App\Core\Domain\Protocols\OrderDirection;
use App\Core\Domain\Protocols\PaginationProps;
use App\Presentation\Http\Controllers\Movie\FindManyMoviesController;
use App\Presentation\Http\HttpRequest;
use App\Presentation\Http\HttpStatusCodes;

beforeEach(function() {
  $this->findManyMoviesUseCaseMock = Mockery::mock(FindManyMoviesUseCase::class);
  $this->sut = new FindManyMoviesController($this->findManyMoviesUseCaseMock);
});

afterEach(function() {
  Mockery::close();
});

it('should return 400 status code because required field page is missing', function() {
  $httpRequest = new HttpRequest([], []);

  $result = $this->sut->index($httpRequest);
  expect($result->statusCode)->toBe(HttpStatusCodes::BAD_REQUEST);
  expect($result->body['error'])->toBe("Field page is missing");
});

it('should return 400 status code because required field perPage is missing', function() {
  $httpRequest = new HttpRequest([], [
    "page" => 1,
  ]);

  $result = $this->sut->index($httpRequest);
  expect($result->statusCode)->toBe(HttpStatusCodes::BAD_REQUEST);
  expect($result->body['error'])->toBe("Field perPage is missing");
});

it('should return 400 status code because page is not an integer', function() {
  $httpRequest = new HttpRequest([], [
    "page" => "abc",
    "perPage" => 12
  ]);

  $result = $this->sut->index($httpRequest);
  expect($result->statusCode)->toBe(HttpStatusCodes::BAD_REQUEST);
  expect($result->body['error'])->toBe("Field page is invalid");
});

it('should return 400 status code because page is negative', function() {
  $httpRequest = new HttpRequest([], [
    "page" => -1,
    "perPage" => 12
  ]);

  $result = $this->sut->index($httpRequest);
  expect($result->statusCode)->toBe(HttpStatusCodes::BAD_REQUEST);
  expect($result->body['error'])->toBe("Field page is invalid");
});

it('should return 400 status code because perPage is not an integer', function() {
  $httpRequest = new HttpRequest([], [
    "page" => 1,
    "perPage" => "abc"
  ]);

  $result = $this->sut->index($httpRequest);
  expect($result->statusCode)->toBe(HttpStatusCodes::BAD_REQUEST);
  expect($result->body['error'])->toBe("Field perPage is invalid");
});

it('should return 400 status code because perPage is negative', function() {
  $httpRequest = new HttpRequest([], [
    "page" => 1,
    "perPage" => -1
  ]);

  $result = $this->sut->index($httpRequest);
  expect($result->statusCode)->toBe(HttpStatusCodes::BAD_REQUEST);
  expect($result->body['error'])->toBe("Field perPage is invalid");
});

it('should return 400 status code because fieldValue is not a string', function() {
  $httpRequest = new HttpRequest([], [
    "page" => 1,
    "perPage" => 12,
    "fieldValue" => 123
  ]);

  $result = $this->sut->index($httpRequest);
  expect($result->statusCode)->toBe(HttpStatusCodes::BAD_REQUEST);
  expect($result->body['error'])->toBe("Field fieldValue is invalid");
});

it('should return 400 status code because isPublic is not a valid representation of a boolean', function() {
  $httpRequest = new HttpRequest([], [
    "page" => 1,
    "perPage" => 12,
    "isPublic" => "invalidBooleanString"
  ]);

  $result = $this->sut->index($httpRequest);
  expect($result->statusCode)->toBe(HttpStatusCodes::BAD_REQUEST);
  expect($result->body['error'])->toBe("Field isPublic is invalid");
});

it('should return 400 status code because ordering does not match regex', function() {
  $httpRequest = new HttpRequest([], [
    "page" => 1,
    "perPage" => 12,
    "ordering" => "*(&!((*! )W(!W("
  ]);

  $result = $this->sut->index($httpRequest);
  expect($result->statusCode)->toBe(HttpStatusCodes::BAD_REQUEST);
  expect($result->body['error'])->toBe("Invalid ordering input. It should be a comma-separated string with field names for ordering, with an optional minus sign prefix to indicate descending order.");
});

it('should call use case with right values and return what it return', function() {
  $httpRequest = new HttpRequest([], [
    "page" => 1,
    "perPage" => 12,
    "fieldValue" => "fieldValue",
    "isPublic" => true,
    "ordering" => "title,-addedAt"
  ]);

  $outputDto = new FindManyMoviesOutputDTO([], new PaginationProps(1, 10));
  $this->findManyMoviesUseCaseMock->shouldReceive('execute')->with(Mockery::on(function ($argument) {
    return $argument->paginationProps->page === 1 &&
           $argument->paginationProps->perPage === 12 &&
           $argument->filterMoviesProps->fieldValue === "fieldValue" &&
           $argument->filterMoviesProps->isPublic === true &&
           $argument->orderMoviesProps->orderByProps[0]->fieldName === "title" &&
           $argument->orderMoviesProps->orderByProps[0]->direction === OrderDirection::ASC &&
           $argument->orderMoviesProps->orderByProps[1]->fieldName === "addedAt" &&
           $argument->orderMoviesProps->orderByProps[1]->direction === OrderDirection::DESC;
  }))->andReturn($outputDto);

  $result = $this->sut->index($httpRequest);
  expect($result->statusCode)->toBe(HttpStatusCodes::OK);
  expect($result->body['data'])->toBe($outputDto);
});