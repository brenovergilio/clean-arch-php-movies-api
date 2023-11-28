<?php
use App\Presentation\Validations\Exceptions\InvalidOrderInputException;
use App\Presentation\Validations\OrderInputValidation;

beforeEach(function() {
  $this->fieldName = "order";
  $this->sut = new OrderInputValidation($this->fieldName);
});

it('should return an InvalidOrderInputException because it does not match regex', function() {
  $input = ["order" => "-$@*#@&*A*ASHU,AISJAHSu"];
  $result = $this->sut->validate($input);
  
  expect($result)->toBeInstanceOf(InvalidOrderInputException::class);
});

it('should return null because it matches regex', function() {
  $input = ["order" => "title,-synopsis"];
  $result = $this->sut->validate($input);
  
  expect($result)->toBeNull();
});
