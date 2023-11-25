<?php
use App\Core\Application\Interfaces\UploadableFile;
use App\Core\Domain\Exceptions\InvalidFieldException;
use App\Presentation\Validations\InstanceOfValidation;

it('should return an InvalidFieldException because field is not an UploadableFile instance', function() {
  $sut = new InstanceOfValidation("field", UploadableFile::class);
  $input = ["field" => "abc"];
  $result = $sut->validate($input);

  expect($result)->toBeInstanceOf(InvalidFieldException::class);
});

it('should return null because field is an UploadableFile instance', function() {
  $uploadableFile = Mockery::mock(UploadableFile::class);
  $sut = new InstanceOfValidation("field", UploadableFile::class);
  $input = ["field" => $uploadableFile];
  $result = $sut->validate($input);
  
  expect($result)->toBeNull();

  Mockery::close();
});
