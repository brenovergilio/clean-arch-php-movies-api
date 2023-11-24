<?php
use App\Core\Application\UseCases\BaseUseCase;
use App\Models\UserModel;

class BaseUseCaseTest extends BaseUseCase {}

beforeEach(function() {
  $this->sut = new BaseUseCaseTest();
});

it("should throw an InsufficientPermissionsException when calling checkAdmin() because user role is client", function() {
  $user = UserModel::factory()->client()->makeOne()->mapToDomain();

  expect(function() use ($user) {
    $this->sut->checkAdmin($user);
  })->toThrow("Insufficient Permissions");
});

it("should throw an InsufficientPermissionsException when calling checkSameUser() because user IDs differ", function() {
  $user = UserModel::factory()->makeOne([
    "id" => 1
  ])->mapToDomain();
  
  expect(function() use ($user){
    $this->sut->checkSameUser($user, "anotherId");
  })->toThrow("Insufficient Permissions");
});

it("should throw an InsufficientPermissionsException when calling checkEmailConfirmed() because user email is not confirmed", function() {
  $user = UserModel::factory()->unverified()->makeOne()->mapToDomain();
  
  expect(function() use ($user){
    $this->sut->checkEmailConfirmed($user);
  })->toThrow("Insufficient Permissions");
});