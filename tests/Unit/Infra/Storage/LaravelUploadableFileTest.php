<?php
use App\Core\Application\Interfaces\Folders;
use App\Infra\Storage\LaravelUploadableFile;
use Illuminate\Http\UploadedFile;

beforeEach(function() {
  $this->uploadableFile = Mockery::mock(UploadedFile::class);
  $this->sut = new LaravelUploadableFile($this->uploadableFile);
});

afterEach(function() {
  Mockery::close();
});

it("should return path", function() {
  $this->uploadableFile->shouldReceive('store')->andReturn("path");
  expect($this->sut->upload(Folders::USERS))->toBe("path");
});

it("should call store with right folders", function() {
  $this->uploadableFile->shouldReceive('store')->with(Folders::USERS->value)->andReturn("path");
  expect($this->sut->upload(Folders::USERS))->toBe("path");

  $this->uploadableFile->shouldReceive('store')->with(Folders::COVERS->value)->andReturn("path");
  expect($this->sut->upload(Folders::COVERS))->toBe("path");
});