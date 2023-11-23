<?php
use App\Infra\Handlers\JWTHandler;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

beforeEach(function() {
  $this->originalObject = new stdClass;
  $this->secret = env('JWT_SECRET', 'secret');
  $this->sut = new JWTHandler();
});

it('should match all tokenized values with the original object values', function() {
  $this->originalObject->id = 'id';
  $this->originalObject->email = 'email';

  $result = $this->sut->generate($this->originalObject, ['id', 'email']);
  $decodedResult = JWT::decode($result, new Key($this->secret, 'HS256'));

  expect($this->originalObject->id)->toBe($decodedResult->id);
  expect($this->originalObject->email)->toBe($decodedResult->email);
});


it('should tokenize only id', function() {
  $this->originalObject->id = 'id';
  $this->originalObject->email = 'email';

  $result = $this->sut->generate($this->originalObject, ['id']);
  $decodedResult = JWT::decode($result, new Key($this->secret, 'HS256'));

  expect($this->originalObject->id)->toBe($decodedResult->id);
  expect(property_exists($decodedResult, 'email'))->toBeFalse();
});

it('should have expiration property', function() {
  $this->originalObject->id = 'id';
  $this->originalObject->email = 'email';

  $result = $this->sut->generate($this->originalObject, ['id', 'email']);
  $decodedResult = JWT::decode($result, new Key($this->secret, 'HS256'));

  expect(property_exists($decodedResult, 'exp'))->toBeTrue();
});

it('should decode token properly', function() {
  $arrayToTokenize = ["property" => "someProperty"];
  $token = JWT::encode($arrayToTokenize, $this->secret, "HS256");
  $decodedObject = $this->sut->decode($token);

  expect($decodedObject->property)->toBe($arrayToTokenize["property"]);
});