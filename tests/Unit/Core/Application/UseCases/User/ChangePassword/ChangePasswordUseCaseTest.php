<?php
use App\Core\Application\Interfaces\HashComparer;
use App\Core\Application\Interfaces\HashGenerator;
use App\Core\Application\UseCases\User\ChangePassword\ChangePasswordUseCase;
use App\Core\Application\UseCases\User\ChangePassword\DTO\ChangePasswordInputDTO;
use App\Core\Domain\Entities\User\UserRepository;
use App\Models\UserModel;

beforeEach(function() {
  $this->userRepositoryMock = Mockery::mock(UserRepository::class);
  $this->hashGeneratorMock = Mockery::mock(HashGenerator::class);
  $this->hashComparerMock = Mockery::mock(HashComparer::class);
  $this->loggedUser = UserModel::factory()->client()->makeOne([
    "id" => 1,
    "password" => "password"
  ])->mapToDomain();

  $this->inputDto = new ChangePasswordInputDTO(
    'newPassword',
    'newPassword',
    'password'
  );

  $this->sut = new ChangePasswordUseCase(
    $this->userRepositoryMock,
    $this->hashGeneratorMock,
    $this->hashComparerMock,
    $this->loggedUser
  );
});

afterEach(function() {
  Mockery::close();
});

it('should throw a PasswordAndConfirmationMismatchException because new password and new password confirmation are different', function() {
  $this->inputDto->newPasswordConfirmation = "differentPassword";

  expect(function() {
    $this->sut->execute($this->inputDto);
  })->toThrow("Password and confirmation are not equal");
});

it('should throw an OldPasswordIsWrongException because old password provided does not match the actual old password', function() {
  $this->userRepositoryMock->shouldReceive('findByID')->andReturn($this->loggedUser);
  $this->hashComparerMock->shouldReceive('compare')->andReturn(false);

  expect(function() {
    $this->sut->execute($this->inputDto);
  })->toThrow("Old password is wrong");
});

it('should call compare() with right values', function() {
  $this->userRepositoryMock->shouldReceive('findByID')->andReturn($this->loggedUser);
  $this->userRepositoryMock->shouldReceive('update');
  $this->hashComparerMock->shouldReceive('compare')->with($this->loggedUser->password(), $this->inputDto->oldPassword)->andReturn(true)->once();
  $this->hashGeneratorMock->shouldReceive('generate');

  $this->sut->execute($this->inputDto);
});


it('should call generate() with right value', function() {
  $this->userRepositoryMock->shouldReceive('findByID')->andReturn($this->loggedUser);
  $this->userRepositoryMock->shouldReceive('update');
  $this->hashComparerMock->shouldReceive('compare')->andReturn(true);
  $this->hashGeneratorMock->shouldReceive('generate')->with($this->inputDto->newPassword)->once();

  $this->sut->execute($this->inputDto);
});

it('should call update() with right values', function() {
  $this->userRepositoryMock->shouldReceive('findByID')->andReturn($this->loggedUser);
  $this->hashComparerMock->shouldReceive('compare')->andReturn(true);
  $this->hashGeneratorMock->shouldReceive('generate')->andReturn('newHashedPassword');
  
  $this->userRepositoryMock->shouldReceive('update')->with(Mockery::on(function ($argument) {
    return $argument->password() === 'newHashedPassword';
  }))->once();

  $this->sut->execute($this->inputDto);
});