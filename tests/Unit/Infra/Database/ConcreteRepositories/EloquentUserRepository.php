<?php
use App\Infra\Database\ConcreteRepositories\EloquentUserRepository;
use App\Models\UserModel;

beforeEach(function() {
  $this->sut = new EloquentUserRepository();
});

it("should return null when calling findByEmail because user does not exist", function() {
  expect($this->sut->findByEmail('email'))->toBe(null);
});

it("should return user when calling findByEmail because user does exist", function() {
  $user = UserModel::factory()->createOne();
  $result = $this->sut->findByEmail($user->email);

  expect($result->id())->toBe($user->id);
  expect($result->name())->toBe($user->name);
  expect($result->password())->toBe($user->password);
  expect($result->cpf())->toBe($user->cpf);
  expect($result->role())->toBe(UserModel::mapRoleToDomain($user->role));
  expect($result->photo())->toBe($user->photo);
  expect($result->isEmailConfirmed())->toBe($user->email_confirmed);
});

it("should return null when calling findByCPF because user does not exist", function() {
  expect($this->sut->findByEmail('cpf'))->toBe(null);
});

it("should return user when calling findByCPF because user does exist", function() {
  $user = UserModel::factory()->createOne([
    "cpf" => "14629037039"
  ]);
  $result = $this->sut->findByCPF($user->cpf);

  expect($result->id())->toBe($user->id);
  expect($result->name())->toBe($user->name);
  expect($result->password())->toBe($user->password);
  expect($result->cpf())->toBe($user->cpf);
  expect($result->role())->toBe(UserModel::mapRoleToDomain($user->role));
  expect($result->photo())->toBe($user->photo);
  expect($result->isEmailConfirmed())->toBe($user->email_confirmed);
});

it("should return null when calling findByID because user does not exist", function() {
  expect($this->sut->findByID('id'))->toBe(null);
});

it("should return user when calling findByID because user does exist", function() {
  $user = UserModel::factory()->createOne();
  $result = $this->sut->findByID($user->id);

  expect($result->id())->toBe($user->id);
  expect($result->name())->toBe($user->name);
  expect($result->password())->toBe($user->password);
  expect($result->cpf())->toBe($user->cpf);
  expect($result->role())->toBe(UserModel::mapRoleToDomain($user->role));
  expect($result->photo())->toBe($user->photo);
  expect($result->isEmailConfirmed())->toBe($user->email_confirmed);
});

it("should update and return user", function() {
  $user = UserModel::factory()->unverified()->createOne()->mapToDomain();
  
  $user->changeName("Jorge");
  $user->changeEmail("new@mail.com");
  $user->changePhoto("newPhoto");
  $user->changeCPF("14629037039");
  $user->confirmEmail();

  $result = $this->sut->update($user, true);

  expect($result->id())->toBe($user->id());
  expect($result->name())->toBe("Jorge");
  expect($result->cpf())->toBe("14629037039");
  expect($result->photo())->toBe("newPhoto");
  expect($result->isEmailConfirmed())->toBe(true);
});

it("should create and return user", function() {
  $user = UserModel::factory()->unverified()->makeOne([
    "name" => "Jorge",
    "email" => "new@mail.com",
    "cpf" => "14629037039",
    "photo" => "newPhoto"
  ])->mapToDomain();
  
  $result = $this->sut->create($user, true);

  expect($result->name())->toBe("Jorge");
  expect($result->cpf())->toBe("14629037039");
  expect($result->photo())->toBe("newPhoto");
  expect($result->isEmailConfirmed())->toBe(false);
});