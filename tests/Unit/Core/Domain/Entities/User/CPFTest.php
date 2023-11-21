<?php

use App\Core\Domain\Entities\User\CPF;

it("should return a CPF with numbers only", function () {
  $cpf = new CPF("146.290.370-39");
  expect($cpf->getValue())->toBe("14629037039");
});

it("should throw an MissingRequiredFieldException because parameter was null", function () {
  expect(function() {
    new CPF(null);
  })->toThrow("Field CPF is missing");
});

it("should throw an MissingRequiredFieldException because parameter was empty", function () {
  expect(function() {
    new CPF("");
  })->toThrow("Field CPF is missing");
});

it("should throw an InvalidFieldException because parameter does not has 11 digits", function () {
  expect(function() {
    new CPF("146.290.370-392");
  })->toThrow("Field CPF is invalid");
});