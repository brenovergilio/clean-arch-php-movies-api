<?php

use App\Core\Domain\Entities\User\Email;

it("should throw an MissingRequiredFieldException because parameter was null", function () {
  expect(function() {
    new Email(null);
  })->toThrow("Field Email is missing");
});

it("should throw an MissingRequiredFieldException because parameter was empty", function () {
  expect(function() {
    new Email("");
  })->toThrow("Field Email is missing");
});

it("should throw an InvalidFieldException because parameter does not match a valid email", function () {
  expect(function() {
    new Email("invalidEmail");
  })->toThrow("Field Email is invalid");
});

it("should return the email provided", function () {
  $email = new Email("valid@mail.com");
  expect($email->getValue())->toBe("valid@mail.com");
});