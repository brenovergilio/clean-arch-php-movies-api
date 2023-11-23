<?php
use App\Core\Application\UseCases\Movie\DeleteMovie\DeleteMovieUseCase;
use App\Core\Application\UseCases\Movie\DeleteMovie\DTO\DeleteMovieInputDTO;
use App\Core\Domain\Entities\Movie\Movie;
use App\Core\Domain\Entities\Movie\MovieGenre;
use App\Core\Domain\Entities\Movie\MovieRepository;
use App\Core\Domain\Entities\User\User;
use DateTime;

beforeEach(function() {
  $this->movieRepositoryMock = Mockery::mock(MovieRepository::class);
  $this->inputDto = new DeleteMovieInputDTO('id');
  $this->loggedUser = new User("id", "name", "146.290.370-39", "valid@mail.com", "password", \App\Core\Domain\Entities\User\Role::ADMIN, 'photo', true);

  $this->sut = new DeleteMovieUseCase($this->movieRepositoryMock, $this->loggedUser);
});

it("should throw an InsufficientPermissionsException because user is not an admin", function() {
  $this->loggedUser = new User("id", "name", "146.290.370-39", "valid@mail.com", "password", \App\Core\Domain\Entities\User\Role::CLIENT, 'photo', true);
  $this->sut = new DeleteMovieUseCase($this->movieRepositoryMock, $this->loggedUser);

  expect(function() {
    $this->sut->execute($this->inputDto);
  })->toThrow("Insufficient Permissions");
});

it("should throw an EntityNotFoundException because movie does not exist", function() {
  $this->movieRepositoryMock->shouldReceive('findByID')->andReturn(null);

  expect(function() {
    $this->sut->execute($this->inputDto);
  })->toThrow("Entity Movie not found");
});

it("should call delete() methdo with right value", function() {
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
  $this->movieRepositoryMock->shouldReceive('delete')->with($this->inputDto->id)->once();

  $this->sut->execute($this->inputDto);
});