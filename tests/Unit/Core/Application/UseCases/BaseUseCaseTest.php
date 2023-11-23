<?php
use App\Core\Application\UseCases\BaseUseCase;
use App\Core\Domain\Entities\User\Role;

class BaseUseCaseTest extends BaseUseCase {}

beforeEach(function() {
  $this->sut = new BaseUseCaseTest();
});

it("should throw an InsufficientPermissionsException when calling checkRole() because user role is not in the allowed roles", function() {
  expect(function() {
    $this->sut->checkRole([Role::ADMIN], Role::CLIENT);
  })->toThrow("Insufficient Permissions");
});

it("should throw an InsufficientPermissionsException when calling checkSameUser() because user IDs differ", function() {
  $user = new \App\Core\Domain\Entities\User\User("id", "name", "146.290.370-39", "valid@mail.com", "password", \App\Core\Domain\Entities\User\Role::CLIENT, 'photo', true);
  
  expect(function() use ($user){
    $this->sut->checkSameUser($user, "anotherId");
  })->toThrow("Insufficient Permissions");
});

it("should throw an InsufficientPermissionsException when calling checkEmailConfirmed() because user email is not confirmed", function() {
  $user = new \App\Core\Domain\Entities\User\User("id", "name", "146.290.370-39", "valid@mail.com", "password", \App\Core\Domain\Entities\User\Role::CLIENT, 'photo', false);
  
  expect(function() use ($user){
    $this->sut->checkEmailConfirmed($user);
  })->toThrow("Insufficient Permissions");
});