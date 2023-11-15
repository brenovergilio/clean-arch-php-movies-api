<?php

namespace App\Core\Domain\Entities\User;
use App\Core\Domain\Exceptions\InvalidFieldException;
use App\Core\Domain\Exceptions\MissingRequiredFieldException;

class Email {
  const regex = "/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/";

  public function __construct(private ?string $value) {
    $this->validateEmail();
  }

  private function validateEmail() {
    $fieldName = "Email";
    
    if(!isset($this->value)) throw new MissingRequiredFieldException($fieldName);

    if(!preg_match(Email::regex, $this->value)) throw new InvalidFieldException($fieldName);
  }
}