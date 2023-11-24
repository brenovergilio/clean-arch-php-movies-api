<?php
use App\Core\Application\UseCases\User\ChangePassword\ChangePasswordUseCase;
use App\Presentation\Http\Controllers\User\ChangePasswordUserController;
use App\Core\Application\Exceptions\PasswordAndConfirmationMismatchException;
use App\Core\Application\Exceptions\OldPasswordIsWrongException;
use App\Presentation\Http\HttpStatusCodes;
use App\Presentation\Http\HttpRequest;

beforeEach(function() {
  $this->changePasswordUseCaseMock = Mockery::mock(ChangePasswordUseCase::class);
  $this->sut = new ChangePasswordUserController($this->changePasswordUseCaseMock);
});

afterEach(function() {
  Mockery::close();
});

it('should return 400 status code because required field newPassword is missing', function() {
  $body = [];
  $httpRequest = new HttpRequest($body);

  $result = $this->sut->changePassword($httpRequest);
  expect($result->statusCode)->toBe(HttpStatusCodes::BAD_REQUEST);
  expect($result->body['error'])->toBe("Field newPassword is missing");
});

it('should return 400 status code because required field newPasswordConfirmation is missing', function() {
  $body = [
    "newPassword" => "newPassword"
  ];
  $httpRequest = new HttpRequest($body);

  $result = $this->sut->changePassword($httpRequest);
  expect($result->statusCode)->toBe(HttpStatusCodes::BAD_REQUEST);
  expect($result->body['error'])->toBe("Field newPasswordConfirmation is missing");
});

it('should return 400 status code because required field oldPassword is missing', function() {
  $body = [
    "newPassword" => "newPassword",
    "newPasswordConfirmation" => "newPasswordConfirmation"
  ];
  $httpRequest = new HttpRequest($body);

  $result = $this->sut->changePassword($httpRequest);
  expect($result->statusCode)->toBe(HttpStatusCodes::BAD_REQUEST);
  expect($result->body['error'])->toBe("Field oldPassword is missing");
});

it('should return 400 status code because password is weak', function() {
  $body = [
    "newPassword" => "newPassword",
    "newPasswordConfirmation" => "newPasswordConfirmation",
    "oldPassword" => "oldPassword"
  ];
  $httpRequest = new HttpRequest($body);

  $result = $this->sut->changePassword($httpRequest);
  expect($result->statusCode)->toBe(HttpStatusCodes::BAD_REQUEST);
  expect($result->body['error'])->toBe("The password must contain at least one uppercase letter, one lowercase letter, one special character, and one number");
});

it('should return 400 status code because use case thrown PasswordAndConfirmationMismatchException', function() {
  $body = [
    "newPassword" => "SenhaForte123@.",
    "newPasswordConfirmation" => "SenhaForte123@.",
    "oldPassword" => "oldPassword"
  ];
  $this->changePasswordUseCaseMock->shouldReceive('execute')->andThrow(new PasswordAndConfirmationMismatchException);
  $httpRequest = new HttpRequest($body);

  $result = $this->sut->changePassword($httpRequest);
  expect($result->statusCode)->toBe(HttpStatusCodes::BAD_REQUEST);
  expect($result->body['error'])->toBe("Password and confirmation are not equal");
});

it('should return 400 status code because use case thrown OldPasswordIsWrongException', function() {
  $body = [
    "newPassword" => "SenhaForte123@.",
    "newPasswordConfirmation" => "SenhaForte123@.",
    "oldPassword" => "oldPassword"
  ];
  $this->changePasswordUseCaseMock->shouldReceive('execute')->andThrow(new OldPasswordIsWrongException);
  $httpRequest = new HttpRequest($body);

  $result = $this->sut->changePassword($httpRequest);
  expect($result->statusCode)->toBe(HttpStatusCodes::BAD_REQUEST);
  expect($result->body['error'])->toBe("Old password is wrong");
});

it('should return 204 status code in case of success', function() {
  $body = [
    "newPassword" => "SenhaForte123@.",
    "newPasswordConfirmation" => "SenhaForte123@.",
    "oldPassword" => "oldPassword"
  ];
  $this->changePasswordUseCaseMock->shouldReceive('execute')->once();
  $httpRequest = new HttpRequest($body);

  $result = $this->sut->changePassword($httpRequest);
  expect($result->statusCode)->toBe(HttpStatusCodes::NO_CONTENT);
  expect($result->body)->toBeNull();
});