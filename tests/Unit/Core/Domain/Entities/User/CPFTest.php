<?php

namespace Tests\Unit\Core\Domain\Entities\User;

use App\Core\Domain\Entities\User\CPF;

it("should return a CPF with numbers only", function () {
  $cpf = new CPF("146.290.370-39");
  expect($cpf->getValue())->toBe("14629037039");
});