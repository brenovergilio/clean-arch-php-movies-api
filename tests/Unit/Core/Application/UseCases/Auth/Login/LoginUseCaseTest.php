<?php
use App\Core\Application\Interfaces\HashComparer;
use App\Core\Application\Interfaces\TokenGenerator;
use App\Core\Application\UseCases\Auth\Login\DTO\LoginInputDTO;
use App\Core\Application\UseCases\Auth\Login\LoginUseCase;
use App\Core\Domain\Entities\User\UserRepository;
use App\Core\Domain\Entities\User\User;
use App\Models\UserModel;

beforeEach(function() {
  $this->userRepositoryMock = Mockery::mock(UserRepository::class);
  $this->hashComparerMock = Mockery::mock(HashComparer::class);
  $this->tokenGeneratorMock = Mockery::mock(TokenGenerator::class);

  $this->user = UserModel::factory()->client()->make([
    "name" => "name",
    "cpf" => "14629037039",
    "email" => "valid@mail.com",
    "password" => "password",
    "photo" => "photo"
  ])->mapToDomain();
  
  $this->inputDto = new LoginInputDTO(
    $this->user->email(),
    $this->user->password()
  );

  $this->sut = new LoginUseCase(
    $this->userRepositoryMock,
    $this->hashComparerMock,
    $this->tokenGeneratorMock
  );
});

it('should throw an InvalidCredentialsException because user was not found', function() {
  $this->userRepositoryMock->shouldReceive('findByEmail')->andReturn(null);

  expect(function() {
    $this->sut->execute($this->inputDto);
  })->toThrow("Invalid Credentials");
});

it("should throw an InvalidCredentialsException because the password provided does not match user's password", function() {
  $this->userRepositoryMock->shouldReceive('findByEmail')->andReturn($this->user);
  $this->hashComparerMock->shouldReceive('compare')->andReturn(false);

  expect(function() {
    $this->sut->execute($this->inputDto);
  })->toThrow("Invalid Credentials");
});

it("should call generate() with right values", function() {
  $this->userRepositoryMock->shouldReceive('findByEmail')->andReturn($this->user);
  $this->hashComparerMock->shouldReceive('compare')->andReturn(true);
  
  $this->tokenGeneratorMock->shouldReceive('generate')->with(Mockery::on(function ($argument) {
    return $argument->id() === $this->user->id() &&
           $argument->name() === $this->user->name() &&
           $argument->cpf() === $this->user->cpf() &&
           $argument->email() === $this->user->email() &&
           $argument->password() === $this->user->password() &&
           $argument->photo() === $this->user->photo();
  }), [$this->user->id()])->once();

  $this->sut->execute($this->inputDto);
});

it("should return expected data in DTO", function() {
  $this->userRepositoryMock->shouldReceive('findByEmail')->andReturn($this->user);
  $this->hashComparerMock->shouldReceive('compare')->andReturn(true);
  $this->tokenGeneratorMock->shouldReceive('generate')->andReturn("token");

  $result = $this->sut->execute($this->inputDto);
  
  expect($result->id)->toBe($this->user->id());
  expect($result->token)->toBe("token");
  expect($result->isAdmin)->toBe($this->user->isAdmin());
});
