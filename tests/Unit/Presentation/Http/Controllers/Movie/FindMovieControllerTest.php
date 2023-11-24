<?php
use App\Core\Application\UseCases\Movie\FindMovie\DTO\FindMovieOutputDTO;
use App\Core\Application\UseCases\Movie\FindMovie\FindMovieUseCase;
use App\Core\Domain\Entities\Movie\MovieGenre;
use App\Core\Domain\Exceptions\EntityNotFoundException;
use App\Presentation\Http\Controllers\Movie\FindMovieController;
use App\Presentation\Http\HttpStatusCodes;

beforeEach(function() {
  $this->findMovieUseCaseMock = Mockery::mock(FindMovieUseCase::class);
  $this->sut = new FindMovieController($this->findMovieUseCaseMock);
});

afterEach(function() {
  Mockery::close();
});

it('should return 404 status code because use case thrown EntityNotFoundException', function() {
  $this->findMovieUseCaseMock->shouldReceive('execute')->andThrow(new EntityNotFoundException("Entity"));

  $result = $this->sut->show('id');
  expect($result->statusCode)->toBe(HttpStatusCodes::NOT_FOUND);
  expect($result->body['error'])->toBe("Entity Entity not found");
});

it('should return 200 status code in case of success', function() {
  $outputDto = new FindMovieOutputDTO(
    'id',
    'title',
    'synopsis',
    'directorName',
    MovieGenre::ACTION,
    'cover',
    false,
    new DateTime(),
    new DateTime(),
  );
  $this->findMovieUseCaseMock->shouldReceive('execute')->once()->andReturn($outputDto);

  $result = $this->sut->show('id');
  expect($result->statusCode)->toBe(HttpStatusCodes::OK);
  expect($result->body['data'])->toBe($outputDto);
});