<?php
use App\Core\Application\Exceptions\InsufficientPermissionsException;
use App\Core\Application\UseCases\User\FindUser\DTO\FindUserOutputDTO;
use App\Core\Application\UseCases\User\FindUser\FindUserUseCase;
use App\Core\Application\UseCases\User\UpdateUser\UpdateUserUseCase;
use App\Presentation\Http\Controllers\User\FindUserController;
use App\Core\Application\Exceptions\DuplicatedUniqueFieldException;
use App\Core\Domain\Exceptions\EntityNotFoundException;
use App\Presentation\Http\HttpStatusCodes;
use App\Presentation\Http\HttpRequest;

beforeEach(function() {
  $this->findUserUseCaseMock = Mockery::mock(FindUserUseCase::class);
  $this->sut = new FindUserController($this->findUserUseCaseMock);
});

afterEach(function() {
  Mockery::close();
});

it('should return 404 status code because use case thrown EntityNotFoundException', function() {
  $this->findUserUseCaseMock->shouldReceive('execute')->andThrow(new EntityNotFoundException("Entity"));

  $result = $this->sut->show('id');
  expect($result->statusCode)->toBe(HttpStatusCodes::NOT_FOUND);
  expect($result->body['error'])->toBe("Entity Entity not found");
});

it('should return 403 status code because use case thrown InsufficientPermissionsException', function() {
  $outputDto = new FindUserOutputDTO(
    'id',
    'name',
    'cpf',
    'email',
    'photo',
    false,
    true
  );

  $this->findUserUseCaseMock->shouldReceive('execute')->andReturn($outputDto);

  $result = $this->sut->show('id');
  expect($result->statusCode)->toBe(HttpStatusCodes::OK);
  expect($result->body["data"])->toBe($outputDto);
});
