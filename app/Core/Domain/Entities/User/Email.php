<?php

namespace App\Core\Domain\Entities\User;
use App\Core\Domain\Exceptions\InvalidFieldException;
use App\Core\Domain\Exceptions\MissingRequiredFieldException;

class Email {
  const CLASS_NAME = "Email";
  const regex = "/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/";

  public function __construct(private ?string $value) {
    $this->validateEmail();
  }

  function getValue(): ?string {
    return $this->value;
  }

  private function validateEmail() {    
    if(!$this->value) throw new MissingRequiredFieldException(Email::CLASS_NAME);

    if(!preg_match(Email::regex, $this->value)) throw new InvalidFieldException(Email::CLASS_NAME);
  }
}