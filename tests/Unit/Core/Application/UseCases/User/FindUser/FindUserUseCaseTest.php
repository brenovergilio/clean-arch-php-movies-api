<?php
use App\Core\Application\Interfaces\UploadableFile;
use App\Core\Application\UseCases\User\FindUser\DTO\FindUserInputDTO;
use App\Core\Application\UseCases\User\FindUser\FindUserUseCase;
use App\Core\Domain\Entities\User\Role;
use App\Core\Domain\Entities\User\User;
use App\Core\Domain\Entities\User\UserRepository;

beforeEach(function() {
  $this->userRepositoryMock = Mockery::mock(UserRepository::class);
  $this->inputDto = new FindUserInputDTO(
    'id',
  );

  $this->sut = new FindUserUseCase(
    $this->userRepositoryMock
  );
});

it('should throw an EntityNotFoundException user was not found', function() {
  $this->userRepositoryMock->shouldReceive('findByID')->andReturn(null);  

  expect(function() {
    $this->sut->execute($this->inputDto);
  })->toThrow("Entity User not found");
});

it('should return the DTO representing the found user', function() {
  $user = new User("id", "name", "146.290.370-39", "valid@mail.com", "password", \App\Core\Domain\Entities\User\Role::CLIENT, 'photo', true);
  $this->userRepositoryMock->shouldReceive('findByID')->andReturn($user);  

  $result = $this->sut->execute($this->inputDto);

  expect($result->id)->toBe($user->id());
  expect($result->name)->toBe($user->name());
  expect($result->cpf)->toBe($user->cpf());
  expect($result->email)->toBe($user->email());
  expect($result->photo)->toBe($user->photo());
  expect($result->isAdmin)->toBe($user->isAdmin());
  expect($result->emailConfirmed)->toBe($user->isEmailConfirmed());
});