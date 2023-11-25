<?php

namespace App\Presentation\Http\Controllers\User;
use App\Core\Application\Interfaces\UploadableFile;
use App\Presentation\Validations\Adapters\CPFValidatorAdapter;
use App\Presentation\Validations\Adapters\EmailValidatorAdapter;
use App\Presentation\Validations\CPFValidation;
use App\Presentation\Validations\EmailValidation;
use App\Presentation\Validations\InstanceOfValidation;
use App\Presentation\Validations\PasswordValidation;
use App\Presentation\Validations\PrimitiveTypeValidation;
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
    $validations[] = new PrimitiveTypeValidation('name', 'string');
    $validations[] = new PasswordValidation('password');

    foreach($fields as $field) {
      if($field === "cpf") $validations[] = new CPFValidation('cpf', new CPFValidatorAdapter());
      if($field === "photo") $validations[] = new InstanceOfValidation('photo', UploadableFile::class);
    }

    return new ValidationComposite($validations);
  }

  public static function updateUserValidations(array $fields): ValidationComposite {
    $validations = [];

    foreach($fields as $field) {
      if($field == 'cpf') $validations[] = new CPFValidation('cpf', new CPFValidatorAdapter());
      if($field == 'email') $validations[] = new EmailValidation('email', new EmailValidatorAdapter());
      if($field == 'photo') $validations[] = new InstanceOfValidation('photo', UploadableFile::class);
      if($field == 'name') $validations[] = new PrimitiveTypeValidation('name', 'string');
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
    $validations = [new RequiredFieldValidation('accessToken'), new PrimitiveTypeValidation('accessToken', 'string')];
    return new ValidationComposite($validations);
  }
}