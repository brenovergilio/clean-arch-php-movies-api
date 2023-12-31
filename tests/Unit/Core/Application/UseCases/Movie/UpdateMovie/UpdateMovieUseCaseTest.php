<?php

use App\Core\Application\Interfaces\FileManipulator;
use App\Core\Application\Interfaces\Folders;
use App\Core\Application\UseCases\Movie\UpdateMovie\DTO\UpdateMovieInputDTO;
use App\Core\Application\UseCases\Movie\UpdateMovie\UpdateMovieUseCase;
use App\Core\Domain\Entities\Movie\MovieGenre;
use App\Core\Domain\Entities\Movie\MovieRepository;
use App\Core\Application\Interfaces\UploadableFile;
use App\Models\MovieModel;
use App\Models\UserModel;

beforeEach(function() {
  $this->movieRepositoryMock = Mockery::mock(MovieRepository::class);
  $this->fileManipulatorMock = Mockery::mock(FileManipulator::class);
  $this->loggedUser = UserModel::factory()->makeOne([
    "id" => 1,
    "name" => "name",
    "cpf" => "14629037039",
    "email" => "valid@mail.com",
    "password" => "password",
    "photo" => "photo"
  ])->mapToDomain();  
  
  $this->now = new DateTime();
  $this->movie = MovieModel::factory()->makeOne([
    "cover" => null
  ])->mapToDomain();

  $this->inputDto = new UpdateMovieInputDTO(
    'id',
    'anotherTitle',
    'anotherSynopsis',
    'anotherDirectorName',
    MovieGenre::COMEDY,
    null,
    false,
    $this->now
  );

  $this->sut = new UpdateMovieUseCase($this->movieRepositoryMock, $this->fileManipulatorMock, $this->loggedUser);
});

afterEach(function() {
  Mockery::close();
});


it("should throw an InsufficientPermissionsException because user is not an admin", function() {
  $this->loggedUser = UserModel::factory()->client()->makeOne()->mapToDomain();
  $this->sut = new UpdateMovieUseCase($this->movieRepositoryMock, $this->fileManipulatorMock, $this->loggedUser);

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

it("should call upload() method with right folder value if a cover file is provided", function() {
  $uploadableFile = Mockery::mock(UploadableFile::class);
  $uploadableFile->shouldReceive('upload')->with(Folders::COVERS)->once();

  $this->movieRepositoryMock->shouldReceive('findByID')->andReturn($this->movie);
  $this->movieRepositoryMock->shouldReceive('update');

  $this->inputDto->cover = $uploadableFile;
  
  $this->sut->execute($this->inputDto);
});

it("should try to delete old cover if movie already has one and another one is passed", function() {
  $this->movie = MovieModel::factory()->makeOne()->mapToDomain();
  
  $uploadableFile = Mockery::mock(UploadableFile::class);
  $uploadableFile->shouldReceive('upload');

  $this->fileManipulatorMock->shouldReceive('exists')->with($this->movie->cover())->andReturn(true);
  $this->fileManipulatorMock->shouldReceive('delete')->with($this->movie->cover())->once();

  $this->movieRepositoryMock->shouldReceive('findByID')->andReturn($this->movie);
  $this->movieRepositoryMock->shouldReceive('update');

  $this->inputDto->cover = $uploadableFile;
  
  $this->sut->execute($this->inputDto);
});

it("should not try to delete old cover if a new cover is passed because old one does not exists in storage", function() {
  $this->movie = MovieModel::factory()->makeOne()->mapToDomain();
  
  $uploadableFile = Mockery::mock(UploadableFile::class);
  $uploadableFile->shouldReceive('upload');

  $this->fileManipulatorMock->shouldReceive('exists')->with($this->movie->cover())->andReturn(false);
  
  $this->movieRepositoryMock->shouldReceive('findByID')->andReturn($this->movie);
  $this->movieRepositoryMock->shouldReceive('update');
  
  $this->inputDto->cover = $uploadableFile;
  
  $this->fileManipulatorMock->shouldNotHaveReceived('delete');
  $this->sut->execute($this->inputDto);
});


it("should call update() method with right values", function() {  
  $updatedMovie = MovieModel::factory()->makeOne([
    'title' => $this->inputDto->title,
    'synopsis' => $this->inputDto->synopsis,
    'director_name' => $this->inputDto->directorName,
    'genre' => MovieModel::mapGenreToModel($this->inputDto->genre),
    'cover' => $this->inputDto->cover,
    'is_public' => $this->inputDto->isPublic,
    'release_date' => $this->inputDto->releaseDate,
    'created_at' => $this->now
  ])->mapToDomain();

  $this->movieRepositoryMock->shouldReceive('findByID')->andReturn($this->movie);
  
  $this->movieRepositoryMock->shouldReceive('update')->with(Mockery::on(function ($argument) use ($updatedMovie) {
    return $argument->title() === $updatedMovie->title() &&
           $argument->synopsis() === $updatedMovie->synopsis() &&
           $argument->directorName() === $updatedMovie->directorName() &&
           $argument->genre() === $updatedMovie->genre() &&
           $argument->cover() === $updatedMovie->cover() &&
           $argument->isPublic() === $updatedMovie->isPublic();
  }))->once();
  
  $this->sut->execute($this->inputDto);
});