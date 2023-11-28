<?php
use App\Core\Application\Interfaces\FileManipulator;
use App\Core\Application\UseCases\Movie\DeleteMovie\DeleteMovieUseCase;
use App\Core\Application\UseCases\Movie\DeleteMovie\DTO\DeleteMovieInputDTO;
use App\Core\Domain\Entities\Movie\MovieRepository;
use App\Models\MovieModel;
use App\Models\UserModel;

beforeEach(function() {
  $this->movieRepositoryMock = Mockery::mock(MovieRepository::class);
  $this->fileManipulatorMock = Mockery::mock(FileManipulator::class);
  $this->inputDto = new DeleteMovieInputDTO('id');
  $this->loggedUser = UserModel::factory()->makeOne([
    "id" => 1,
    "name" => "name",
    "cpf" => "14629037039",
    "email" => "valid@mail.com",
    "password" => "password",
    "photo" => "photo"
  ])->mapToDomain();

  $this->sut = new DeleteMovieUseCase($this->movieRepositoryMock, $this->fileManipulatorMock, $this->loggedUser);
});

afterEach(function() {
  Mockery::close();
});


it("should throw an InsufficientPermissionsException because user is not an admin", function() {
  $this->loggedUser = UserModel::factory()->client()->makeOne()->mapToDomain();
  $this->sut = new DeleteMovieUseCase($this->movieRepositoryMock, $this->fileManipulatorMock, $this->loggedUser);

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

it("should call delete() method with right value", function() {
  $movie = MovieModel::factory()->makeOne()->mapToDomain();

  $this->movieRepositoryMock->shouldReceive('findByID')->andReturn($movie);
  $this->movieRepositoryMock->shouldReceive('delete')->with($this->inputDto->id)->once();
  $this->fileManipulatorMock->shouldReceive('exists');
  $this->fileManipulatorMock->shouldReceive('delete');

  $this->sut->execute($this->inputDto);
});

it("should try to delete movie cover, if exists", function() {
  $movie = MovieModel::factory()->makeOne()->mapToDomain();

  $this->movieRepositoryMock->shouldReceive('findByID')->andReturn($movie);
  $this->movieRepositoryMock->shouldReceive('delete');

  $this->fileManipulatorMock->shouldReceive('exists')->with($movie->cover())->once()->andReturn(true);
  $this->fileManipulatorMock->shouldReceive('delete')->with($movie->cover())->once();

  $this->sut->execute($this->inputDto);
});

it("should not try to delete movie cover, if it does not exist", function() {
  $movie = MovieModel::factory()->makeOne()->mapToDomain();

  $this->movieRepositoryMock->shouldReceive('findByID')->andReturn($movie);
  $this->movieRepositoryMock->shouldReceive('delete');

  $this->fileManipulatorMock->shouldReceive('exists')->with($movie->cover())->once()->andReturn(false);
  $this->fileManipulatorMock->shouldNotHaveReceived('delete');

  $this->sut->execute($this->inputDto);
});