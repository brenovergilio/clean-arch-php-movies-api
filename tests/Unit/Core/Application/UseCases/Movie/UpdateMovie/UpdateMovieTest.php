<?php

use App\Core\Application\UseCases\Movie\UpdateMovie\DTO\UpdateMovieInputDTO;
use App\Core\Application\UseCases\Movie\UpdateMovie\UpdateMovieUseCase;
use App\Core\Domain\Entities\Movie\MovieGenre;
use App\Core\Domain\Entities\Movie\Movie;
use App\Core\Domain\Entities\Movie\MovieRepository;
use App\Core\Domain\Entities\User\User;
use App\Core\Application\Interfaces\UploadableFile;
use DateTime;

beforeEach(function() {
  $this->movieRepositoryMock = Mockery::mock(MovieRepository::class);
  $this->loggedUser = new User("id", "name", "146.290.370-39", "valid@mail.com", "password", \App\Core\Domain\Entities\User\Role::ADMIN, 'photo', true);
  $this->now = new DateTime();
  $this->movie = new Movie(
    'id',
    'title',
    'synopsis',
    'directorName',
    MovieGenre::ACTION,
    null,
    true,
    $this->now,
    $this->now,
  );

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

  $this->sut = new UpdateMovieUseCase($this->movieRepositoryMock, $this->loggedUser);
});

it("should throw an InsufficientPermissionsException because user is not an admin", function() {
  $this->loggedUser = new User("id", "name", "146.290.370-39", "valid@mail.com", "password", \App\Core\Domain\Entities\User\Role::CLIENT, 'photo', true);
  $this->sut = new UpdateMovieUseCase($this->movieRepositoryMock, $this->loggedUser);

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

it("should call upload() method if a cover file is provided", function() {
  $uploadableFile = Mockery::mock(UploadableFile::class);
  $uploadableFile->shouldReceive('upload')->once();

  $this->movieRepositoryMock->shouldReceive('findByID')->andReturn($this->movie);
  $this->movieRepositoryMock->shouldReceive('update');

  $this->inputDto->cover = $uploadableFile;
  
  $this->sut->execute($this->inputDto);
});

it("should call update() method with right values", function() {  
  $updatedMovie = new Movie(
    'id',
    $this->inputDto->title,
    $this->inputDto->synopsis,
    $this->inputDto->directorName,
    $this->inputDto->genre,
    $this->inputDto->cover,
    $this->inputDto->isPublic,
    $this->inputDto->releaseDate,
    $this->now,
  );

  $this->movieRepositoryMock->shouldReceive('findByID')->andReturn($this->movie);
  
  $this->movieRepositoryMock->shouldReceive('update')->with(Mockery::on(function ($argument) use ($updatedMovie) {
    return $argument->title() === $updatedMovie->title() &&
           $argument->synopsis() === $updatedMovie->synopsis() &&
           $argument->directorName() === $updatedMovie->directorName() &&
           $argument->genre() === $updatedMovie->genre() &&
           $argument->cover() === $updatedMovie->cover() &&
           $argument->releaseDate() === $updatedMovie->releaseDate() &&
           $argument->isPublic() === $updatedMovie->isPublic();
  }))->once();
  
  $this->sut->execute($this->inputDto);
});