<?php

namespace App\Presentation\Http\Controllers\Auth;
use App\Presentation\Validations\RequiredFieldValidation;
use App\Presentation\Validations\ValidationComposite;

class AuthControllerValidations {
  public static function loginValidations(array $fields): ValidationComposite {
    $validations = [];
    $requiredFields = ['email', 'password'];

    foreach($requiredFields as $requiredField) {
      $validations[] = new RequiredFieldValidation($requiredField);
    }

    return new ValidationComposite($validations);
  }
}