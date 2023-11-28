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

it("should throw an InvalidFieldException because CPF is invalid", function () {
  expect(function() {
    new CPF("111.111.111-11");
  })->toThrow("Field CPF is invalid");
});

it("should throw no errors because CPF is valid", function () {
  $cpf = new CPF("146.290.370-39");
  expect($cpf->getValue())->toBe("14629037039");
});