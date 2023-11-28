<?php

use App\Core\Domain\Helpers;

it('should generate different length strings depending on the parameter', function($length) {
  $result = Helpers::generateRandomString($length);
  $resultLength = strlen($result);
  
  if($length < 1) expect($resultLength)->toBe(0);
  else expect($resultLength)->toBe($length);
})->with([-1, 0, 1, 3, 6, 9]);

it("should convert string 'false' to false", function() {
  $result = Helpers::convertStringBoolToPrimitive('false');
  
  expect($result)->toBe(false);
});

it("should convert string 'true' to true", function() {
  $result = Helpers::convertStringBoolToPrimitive('true');
  
  expect($result)->toBe(true);
});

it("should return false as itself", function() {
  $result = Helpers::convertStringBoolToPrimitive(false);
  
  expect($result)->toBe(false);
});

it("should return true as itself", function() {
  $result = Helpers::convertStringBoolToPrimitive(true);
  
  expect($result)->toBe(true);
});

