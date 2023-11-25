<?php

namespace App\Presentation\Http\Controllers\Auth;
use App\Presentation\Validations\PrimitiveTypeValidation;
use App\Presentation\Validations\RequiredFieldValidation;
use App\Presentation\Validations\ValidationComposite;
use App\Presentation\Validations\EmailValidation;
use App\Presentation\Validations\Adapters\EmailValidatorAdapter;

class AuthControllerValidations {
  public static function loginValidations(array $fields): ValidationComposite {
    $validations = [];
    $requiredFields = ['email', 'password'];

    foreach($requiredFields as $requiredField) {
      $validations[] = new RequiredFieldValidation($requiredField);
    }

    $validations[] = new EmailValidation('email', new EmailValidatorAdapter());
    $validations[] = new PrimitiveTypeValidation('password', 'string');

    return new ValidationComposite($validations);
  }
}