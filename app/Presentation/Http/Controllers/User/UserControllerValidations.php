<?php

namespace App\Presentation\Http\Controllers\User;
use App\Presentation\Validations\Adapters\CPFValidatorAdapter;
use App\Presentation\Validations\Adapters\EmailValidatorAdapter;
use App\Presentation\Validations\CPFValidation;
use App\Presentation\Validations\EmailValidation;
use App\Presentation\Validations\PasswordValidation;
use App\Presentation\Validations\RequiredFieldValidation;
use App\Presentation\Validations\ValidationComposite;

class UserControllerValidations {
  public static function createUserValidations(array $fields): ValidationComposite {
    $validations = [];
    $requiredFields = ['name', 'email', 'password', 'passwordConfirmation'];

    foreach($requiredFields as $requiredField) {
      $validations[] = new RequiredFieldValidation($requiredField);
    }

    $validations[] = new EmailValidation('email', new EmailValidatorAdapter());
    
    if(in_array('cpf', $fields)) {
      $validations[] = new CPFValidation('cpf', new CPFValidatorAdapter());
    }

    $validations[] = new PasswordValidation('password');

    return new ValidationComposite($validations);
  }

  public static function updateUserValidations(array $fields): ValidationComposite {
    $validations = [];

    if(in_array('cpf', $fields)) {
      $validations[] = new CPFValidation('cpf', new CPFValidatorAdapter());
    }

    if(in_array('email', $fields)) {
      $validations[] = new EmailValidation('email', new EmailValidatorAdapter());
    }

    return new ValidationComposite($validations);
  }

  public static function changePasswordValidations(): ValidationComposite {
    $validations = [];
    $requiredFields = ['newPassword', 'newPasswordConfirmation', 'oldPassword'];

    foreach($requiredFields as $requiredField) {
      $validations[] = new RequiredFieldValidation($requiredField);
    }

    $validations[] = new PasswordValidation('newPassword');

    return new ValidationComposite($validations);
  }

  public static function confirmEmailValidations(): ValidationComposite {
    $validations = [new RequiredFieldValidation('accessToken')];
    return new ValidationComposite($validations);
  }
}