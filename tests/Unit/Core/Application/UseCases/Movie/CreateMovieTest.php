<?php

use App\Core\Application\UseCases\Movie\CreateMovie\CreateMovieUseCase;
use App\Core\Application\UseCases\Movie\CreateMovie\DTO\CreateMovieInputDTO;
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

  $this->inputDto = new CreateMovieInputDTO(
    'title',
    'synopsis',
    'directorName',
    MovieGenre::ACTION,
    null,
    true,
    $this->now
  );

  $this->sut = new CreateMovieUseCase($this->movieRepositoryMock, $this->loggedUser);
});

it("should throw an InsufficientPermissionsException because user is not an admin", function() {
  $this->loggedUser = new User("id", "name", "146.290.370-39", "valid@mail.com", "password", \App\Core\Domain\Entities\User\Role::CLIENT, 'photo', true);
  $this->sut = new CreateMovieUseCase($this->movieRepositoryMock, $this->loggedUser);

  expect(function() {
    $this->sut->execute($this->inputDto);
  })->toThrow("Insufficient Permissions");
});

it("should call upload() method if a cover file is provided", function() {
  $uploadableFile = Mockery::mock(UploadableFile::class);
  $uploadableFile->shouldReceive('upload')->once();
  
  $this->movieRepositoryMock->shouldReceive('create');

  $this->inputDto->cover = $uploadableFile;
  
  $this->sut->execute($this->inputDto);
});

it("should call create() method with right values", function() {
  $createdMovie = new Movie(
    null,
    $this->inputDto->title,
    $this->inputDto->synopsis,
    $this->inputDto->directorName,
    $this->inputDto->genre,
    $this->inputDto->cover,
    $this->inputDto->isPublic,
    $this->inputDto->releaseDate,
    $this->now,
  );
  
  $this->movieRepositoryMock->shouldReceive('create')->with(Mockery::on(function ($argument) use ($createdMovie) {
    return $argument->title() === $createdMovie->title() &&
           $argument->synopsis() === $createdMovie->synopsis() &&
           $argument->directorName() === $createdMovie->directorName() &&
           $argument->genre() === $createdMovie->genre() &&
           $argument->cover() === $createdMovie->cover() &&
           $argument->releaseDate() === $createdMovie->releaseDate() &&
           $argument->isPublic() === $createdMovie->isPublic();
  }))->once();
  
  $this->sut->execute($this->inputDto);
});