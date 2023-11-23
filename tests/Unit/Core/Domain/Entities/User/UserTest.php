<?php
use App\Core\Domain\Entities\User\Role;
use App\Core\Domain\Entities\User\User;

beforeEach(function() {
  $this->user = new User("id", "Name", "146.290.370-39", "valid@mail.com", "hashedPassword", Role::ADMIN, "pathToPhoto", true);
});

it("should return the right values when using getters", function () {
  expect($this->user->id())->toBe("id");
  expect($this->user->name())->toBe("Name");
  expect($this->user->cpf())->toBe("14629037039");
  expect($this->user->email())->toBe("valid@mail.com");
  expect($this->user->password())->toBe("hashedPassword");
  expect($this->user->photo())->toBe("pathToPhoto");
});

it("should return true when calling isAdmin()", function() {
  expect($this->user->isAdmin())->toBeTrue();
});

it("should return true when calling isEmailConfirmed()", function() {
  expect($this->user->isEmailConfirmed())->toBeTrue();
});

it("should return confirm user's email", function() {
  $user = new User("id", "name", "146.290.370-39", "valid@mail.com", "hashedPassword", Role::ADMIN, "pathToPhoto", false);
  expect($user->isEmailConfirmed())->toBeFalse();
  $user->confirmEmail();
  expect($user->isEmailConfirmed())->toBeTrue();
});

it("should change user's password", function() {
  expect($this->user->password())->toBe("hashedPassword");
  $this->user->changePassword("newHashedPassword");
  expect($this->user->password())->toBe("newHashedPassword");
});

it("should change user's photo path", function() {
  expect($this->user->photo())->toBe("pathToPhoto");
  $this->user->changePhoto("pathToNewPhoto");
  expect($this->user->photo())->toBe("pathToNewPhoto");
});

it("should change user's name", function() {
  expect($this->user->name())->toBe("Name");
  $this->user->changeName("Another Name");
  expect($this->user->name())->toBe("Another Name");
});

it("should change user's email and set emailConfirmed property to false after that", function() {
  expect($this->user->email())->toBe("valid@mail.com");
  $this->user->changeEmail("anotherValid@mail.com");
  expect($this->user->email())->toBe("anotherValid@mail.com");
  expect($this->user->isEmailConfirmed())->toBeFalse();
});

it("should change user's CPF", function() {
  expect($this->user->cpf())->toBe("14629037039");
  $this->user->changeCPF("904.051.380-59");
  expect($this->user->cpf())->toBe("90405138059");
});