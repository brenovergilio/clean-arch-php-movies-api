<?php
use App\Core\Application\Interfaces\EmailSender;
use App\Core\Application\Interfaces\HashGenerator;
use App\Core\Application\Interfaces\UploadableFile;
use App\Core\Application\UseCases\User\CreateUser\CreateUserUseCase;
use App\Core\Application\UseCases\User\CreateUser\DTO\CreateUserInputDTO;
use App\Core\Domain\Entities\AccessToken\AccessTokenRepository;
use App\Core\Domain\Entities\User\UserRepository;

beforeEach(function() {
  $this->userRepositoryMock = Mockery::mock(UserRepository::class);
  $this->accessTokenRepositoryMock = Mockery::mock(AccessTokenRepository::class);
  $this->hashGeneratorMock = Mockery::mock(HashGenerator::class);
  $this->emailSenderMock = Mockery::mock(EmailSender::class);

  $this->inputDto = new CreateUserInputDTO(
    'Name',
    'valid@mail.com',
    '146.290.370-39',
    'password',
    'password',
    null
  );

  $this->sut = new CreateUserUseCase(
    $this->userRepositoryMock,
    $this->accessTokenRepositoryMock,
    $this->hashGeneratorMock,
    $this->emailSenderMock
  );
});

it("should throw a DuplicatedUniqueFieldException because there is a user with the same email", function() {
  $existingUser = new \App\Core\Domain\Entities\User\User("id", "name", "146.290.370-39", "valid@mail.com", "password", \App\Core\Domain\Entities\User\Role::CLIENT, 'photo', true);
  $this->userRepositoryMock->shouldReceive('findByEmail')->andReturn($existingUser);

  expect(function() {
    $this->sut->execute($this->inputDto);
  })->toThrow("Field Email is already in use");
});

it("should throw a DuplicatedUniqueFieldException because there is a user with the same CPF", function() {
  $existingUser = new \App\Core\Domain\Entities\User\User("id", "name", "146.290.370-39", "valid@mail.com", "password", \App\Core\Domain\Entities\User\Role::CLIENT, 'photo', true);
  $this->userRepositoryMock->shouldReceive('findByEmail')->andReturn(null);
  $this->userRepositoryMock->shouldReceive('findByCPF')->andReturn($existingUser);

  expect(function() {
    $this->sut->execute($this->inputDto);
  })->toThrow("Field CPF is already in use");
});

it("should throw a PasswordAndConfirmationMismatchException because password and confirmation does not match", function() {
  $this->userRepositoryMock->shouldReceive('findByEmail')->andReturn(null);
  $this->userRepositoryMock->shouldReceive('findByCPF')->andReturn(null);

  $this->inputDto->passwordConfirmation = "differentPassword";

  expect(function() {
    $this->sut->execute($this->inputDto);
  })->toThrow("Password and confirmation are not equal");
});

it("should call upload() method on UploadableFile, when photo is provided", function() {
  $user = new \App\Core\Domain\Entities\User\User("id", "Name", "146.290.370-39", "valid@mail.com", "password", \App\Core\Domain\Entities\User\Role::CLIENT, 'photo', true);

  $this->userRepositoryMock->shouldReceive('findByEmail')->andReturn(null);
  $this->userRepositoryMock->shouldReceive('findByCPF')->andReturn(null);
  $this->userRepositoryMock->shouldReceive('create')->andReturn($user);
  $this->hashGeneratorMock->shouldReceive('generate')->andReturn('hashedPassword');
  $this->accessTokenRepositoryMock->shouldReceive('find')->andReturn(null);
  $this->emailSenderMock->shouldReceive('sendMail');

  $uploadableFile = Mockery::mock(UploadableFile::class);
  $uploadableFile->shouldReceive('upload')->once();
 
  $this->inputDto->photo = $uploadableFile;

  $this->sut->execute($this->inputDto);
});

it("should execute flow successfully", function() {
  $createdUser = new \App\Core\Domain\Entities\User\User('id', $this->inputDto->name, $this->inputDto->cpf, $this->inputDto->email, $this->inputDto->password, \App\Core\Domain\Entities\User\Role::CLIENT, $this->inputDto->photo, false);

  $this->userRepositoryMock->shouldReceive('findByEmail')->once()->andReturn(null);
  $this->userRepositoryMock->shouldReceive('findByCPF')->once()->andReturn(null);
  $this->hashGeneratorMock->shouldReceive('generate')->once()->andReturn('hashedPassword');
  $this->accessTokenRepositoryMock->shouldReceive('find')->once()->andReturn(null);
  $this->emailSenderMock->shouldReceive('sendMail')->once();
  $this->userRepositoryMock->shouldReceive('create')->with(Mockery::on(function ($argument) use ($createdUser) {
    return $argument->name() === $createdUser->name() &&
           $argument->cpf() === $createdUser->cpf() &&
           $argument->email() === $createdUser->email() &&
           $argument->password() === "hashedPassword" &&
           $argument->isEmailConfirmed() === false &&
           $argument->isAdmin() === false &&
           $argument->photo() === $createdUser->photo();
  }), true)->once()->andReturn($createdUser);
 
  $this->sut->execute($this->inputDto);
});