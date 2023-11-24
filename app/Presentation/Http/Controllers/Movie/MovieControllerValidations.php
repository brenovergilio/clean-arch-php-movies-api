<?php

namespace App\Presentation\Http\Controllers\Movie;
use App\Presentation\Validations\RequiredFieldValidation;
use App\Presentation\Validations\ValidationComposite;

class MovieControllerValidations {
  public static function createMovieValidations(array $fields): ValidationComposite {
    $validations = [];
    $requiredFields = ['title', 'synopsis', 'directorName', 'genre', 'isPublic', 'releaseDate'];

    foreach($requiredFields as $requiredField) {
      $validations[] = new RequiredFieldValidation($requiredField);
    }

    return new ValidationComposite($validations);
  }
}