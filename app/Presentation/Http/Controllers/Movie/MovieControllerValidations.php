<?php

namespace App\Presentation\Http\Controllers\Movie;
use App\Core\Application\Interfaces\UploadableFile;
use App\Core\Domain\Entities\Movie\MovieGenre;
use App\Presentation\Validations\Adapters\DateTimeValidatorAdapter;
use App\Presentation\Validations\Adapters\IsEnumValidatorAdapter;
use App\Presentation\Validations\DateTimeValidation;
use App\Presentation\Validations\InstanceOfValidation;
use App\Presentation\Validations\IsEnumValidation;
use App\Presentation\Validations\OrderInputValidation;
use App\Presentation\Validations\PrimitiveTypeValidation;
use App\Presentation\Validations\RequiredFieldValidation;
use App\Presentation\Validations\ValidationComposite;

class MovieControllerValidations {
  public static function createMovieValidations(array $fields): ValidationComposite {
    $validations = [];
    $requiredFields = ['title', 'synopsis', 'directorName', 'genre', 'isPublic', 'releaseDate'];

    foreach($requiredFields as $requiredField) {
      $validations[] = new RequiredFieldValidation($requiredField);
    }

    $validations[] = new PrimitiveTypeValidation('title', 'string');
    $validations[] = new PrimitiveTypeValidation('synopsis', 'string');
    $validations[] = new PrimitiveTypeValidation('directorName', 'string');
    $validations[] = new PrimitiveTypeValidation('isPublic', 'bool');
    $validations[] = new IsEnumValidation('genre', new IsEnumValidatorAdapter(), MovieGenre::class);
    $validations[] = new DateTimeValidation('releaseDate', new DateTimeValidatorAdapter());

    if(in_array('cover', $fields)) $validations[] = new InstanceOfValidation('cover', UploadableFile::class);

    return new ValidationComposite($validations);
  }

  public static function updateMovieValidations(array $fields): ValidationComposite {
    $validations = [];

    foreach($fields as $field) {
      if($field === "title") $validations[] = new PrimitiveTypeValidation('title', 'string');
      if($field === "synopsis") $validations[] = new PrimitiveTypeValidation('synopsis', 'string');
      if($field === "directorName") $validations[] = new PrimitiveTypeValidation('directorName', 'string');
      if($field === "isPublic") $validations[] = new PrimitiveTypeValidation('isPublic', 'bool');
      if($field === "genre") $validations[] = new IsEnumValidation('genre', new IsEnumValidatorAdapter(), MovieGenre::class);
      if($field === "releaseDate") $validations[] = new DateTimeValidation('releaseDate', new DateTimeValidatorAdapter());
      if($field ===  "cover") $validations[] = new InstanceOfValidation('cover', UploadableFile::class);
    }

    return new ValidationComposite($validations);
  }

  public static function findManyMoviesValidations(array $fields): ValidationComposite {
    $validations = [];

    foreach($fields as $field) {
      if($field === "page") $validations[] = new PrimitiveTypeValidation('page', 'int');
      if($field === "perPage") $validations[] = new PrimitiveTypeValidation('perPage', 'int');
      if($field === "fieldValue") $validations[] = new PrimitiveTypeValidation('fieldValue', 'string');
      if($field === "isPublic") $validations[] = new PrimitiveTypeValidation('isPublic', 'bool');
      if($field === "ordering") $validations[] = new OrderInputValidation('ordering');
    }

    return new ValidationComposite($validations);
  }
}