<?php
use App\Infra\Storage\LaravelUploadableFile;
use Illuminate\Http\UploadedFile;

it("should return path", function() {
  $uploadableFile = Mockery::mock(UploadedFile::class);
  $uploadableFile->shouldReceive('store')->andReturn("path");
  
  $sut = new LaravelUploadableFile($uploadableFile);
  expect($sut->upload())->toBe("path");

  Mockery::close();
});