<?php
use App\Core\Application\UseCases\User\FindUser\DTO\FindUserInputDTO;
use App\Core\Application\UseCases\User\FindUser\FindUserUseCase;
use App\Core\Domain\Entities\User\UserRepository;
use App\Models\UserModel;

beforeEach(function() {
  $this->userRepositoryMock = Mockery::mock(UserRepository::class);
  $this->inputDto = new FindUserInputDTO(
    'id',
  );

  $this->sut = new FindUserUseCase(
    $this->userRepositoryMock
  );
});

afterEach(function() {
  Mockery::close();
});


it('should throw an EntityNotFoundException user was not found', function() {
  $this->userRepositoryMock->shouldReceive('findByID')->andReturn(null);  

  expect(function() {
    $this->sut->execute($this->inputDto);
  })->toThrow("Entity User not found");
});

it('should return the DTO representing the found user', function() {
  $user = UserModel::factory()->client()->makeOne([
    "id" => 1,
    "cpf" => "14629037039"
  ])->mapToDomain();
  $this->userRepositoryMock->shouldReceive('findByID')->andReturn($user);  

  $result = $this->sut->execute($this->inputDto);

  expect($result->id)->toBe($user->id());
  expect($result->name)->toBe($user->name());
  expect($result->cpf)->toBe($user->cpf());
  expect($result->email)->toBe($user->email());
  expect($result->photo)->toBe($user->photo());
});