<?php

use App\Core\Application\UseCases\Movie\CreateMovie\CreateMovieUseCase;
use App\Core\Application\UseCases\Movie\CreateMovie\DTO\CreateMovieInputDTO;
use App\Core\Domain\Entities\Movie\MovieGenre;
use App\Core\Domain\Entities\Movie\MovieRepository;
use App\Core\Application\Interfaces\UploadableFile;
use App\Models\MovieModel;
use App\Models\UserModel;

beforeEach(function() {
  $this->movieRepositoryMock = Mockery::mock(MovieRepository::class);
  $this->loggedUser = UserModel::factory()->makeOne([
    "id" => 1,
    "name" => "name",
    "cpf" => "14629037039",
    "email" => "valid@mail.com",
    "password" => "password",
    "photo" => "photo"
  ])->mapToDomain();  
  
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

afterEach(function() {
  Mockery::close();
});


it("should throw an InsufficientPermissionsException because user is not an admin", function() {
  $this->loggedUser = UserModel::factory()->client()->makeOne()->mapToDomain();  
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
  $createdMovie = MovieModel::factory()->makeOne([
    'title' => $this->inputDto->title,
    'synopsis' => $this->inputDto->synopsis,
    'director_name' => $this->inputDto->directorName,
    'genre' => MovieModel::mapGenreToModel($this->inputDto->genre),
    'cover' => $this->inputDto->cover,
    'is_public' => $this->inputDto->isPublic,
    'release_date' => $this->inputDto->releaseDate,
    'created_at' => $this->now
  ])->mapToDomain();
  
  $this->movieRepositoryMock->shouldReceive('create')->with(Mockery::on(function ($argument) use ($createdMovie) {
    return $argument->title() === $createdMovie->title() &&
           $argument->synopsis() === $createdMovie->synopsis() &&
           $argument->directorName() === $createdMovie->directorName() &&
           $argument->genre() === $createdMovie->genre() &&
           $argument->cover() === $createdMovie->cover() &&
           $argument->isPublic() === $createdMovie->isPublic();
  }))->once();
  
  $this->sut->execute($this->inputDto);
});