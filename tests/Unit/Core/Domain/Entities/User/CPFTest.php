<?php

use App\Core\Domain\Entities\User\CPF;

it("should return a CPF with numbers only", function () {
  $cpf = new CPF("146.290.370-39");
  expect($cpf->getValue())->toBe("14629037039");
});

it("should return null because parameter was null", function () {
  $cpf = new CPF(null);
  expect($cpf->getValue())->toBe(null);
});

it("should throw an InvalidFieldException because parameter does not has 11 digits", function () {
  expect(function() {
    new CPF("146.290.370-392");
  })->toThrow("Field CPF is invalid");
});