<?php
use App\Core\Application\Interfaces\EmailSender;
use App\Core\Application\Interfaces\Folders;
use App\Core\Application\Interfaces\HashGenerator;
use App\Core\Application\Interfaces\UploadableFile;
use App\Core\Application\UseCases\User\CreateUser\CreateUserUseCase;
use App\Core\Application\UseCases\User\CreateUser\DTO\CreateUserInputDTO;
use App\Core\Domain\Entities\AccessToken\AccessTokenRepository;
use App\Core\Domain\Entities\User\UserRepository;
use App\Models\UserModel;

beforeEach(function() {
  $this->userRepositoryMock = Mockery::mock(UserRepository::class);
  $this->accessTokenRepositoryMock = Mockery::mock(AccessTokenRepository::class);
  $this->hashGeneratorMock = Mockery::mock(HashGenerator::class);
  $this->emailSenderMock = Mockery::mock(EmailSender::class);

  $this->inputDto = new CreateUserInputDTO(
    'Name',
    'valid@mail.com',
    '14629037039',
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

afterEach(function() {
  Mockery::close();
});


it("should throw a DuplicatedUniqueFieldException because there is a user with the same email", function() {
  $existingUser = UserModel::factory()->client()->makeOne()->mapToDomain();
  $this->userRepositoryMock->shouldReceive('findByEmail')->andReturn($existingUser);

  expect(function() {
    $this->sut->execute($this->inputDto);
  })->toThrow("Field Email is already in use");
});

it("should throw a DuplicatedUniqueFieldException because there is a user with the same CPF", function() {
  $existingUser = UserModel::factory()->client()->makeOne()->mapToDomain();
  $this->userRepositoryMock->shouldReceive('findByEmail')->andReturn(null);
  $this->userRepositoryMock->shouldReceive('findByCPF')->andReturn($existingUser);

  expect(function() {
    $this->sut->execute($this->inputDto);
  })->toThrow("Field CPF is already in use");
});

it("should not try to find user by CPF because there is no CPF provided", function() {
  $user = UserModel::factory()->client()->makeOne([
    "id" => 1
  ])->mapToDomain();
  
  $this->userRepositoryMock->shouldReceive('findByEmail')->andReturn(null);
  $this->userRepositoryMock->shouldNotHaveReceived('findByCPF');
  $this->userRepositoryMock->shouldReceive('create')->andReturn($user);
  $this->hashGeneratorMock->shouldReceive('generate')->andReturn('hashedPassword');
  $this->accessTokenRepositoryMock->shouldReceive('find')->andReturn(null);
  $this->emailSenderMock->shouldReceive('sendMail');
  $this->accessTokenRepositoryMock->shouldReceive('create');

  $this->inputDto->cpf = null;
  $this->sut->execute($this->inputDto);
});

it("should throw a PasswordAndConfirmationMismatchException because password and confirmation does not match", function() {
  $this->userRepositoryMock->shouldReceive('findByEmail')->andReturn(null);
  $this->userRepositoryMock->shouldReceive('findByCPF')->andReturn(null);

  $this->inputDto->passwordConfirmation = "differentPassword";

  expect(function() {
    $this->sut->execute($this->inputDto);
  })->toThrow("Password and confirmation are not equal");
});

it("should call upload() method on UploadableFile with right folder value, when photo is provided", function() {
  $user = UserModel::factory()->client()->makeOne([
    "id" => 1
  ])->mapToDomain();

  $this->userRepositoryMock->shouldReceive('findByEmail')->andReturn(null);
  $this->userRepositoryMock->shouldReceive('findByCPF')->andReturn(null);
  $this->userRepositoryMock->shouldReceive('create')->andReturn($user);
  $this->hashGeneratorMock->shouldReceive('generate')->andReturn('hashedPassword');
  $this->accessTokenRepositoryMock->shouldReceive('find')->andReturn(null);
  $this->emailSenderMock->shouldReceive('sendMail');
  $this->accessTokenRepositoryMock->shouldReceive('create');

  $uploadableFile = Mockery::mock(UploadableFile::class);
  $uploadableFile->shouldReceive('upload')->with(Folders::USERS)->once();
 
  $this->inputDto->photo = $uploadableFile;

  $this->sut->execute($this->inputDto);
});

it("should execute flow successfully", function() {
  $createdUser = UserModel::factory()->client()->unverified()->makeOne([
    "id" => 1,
    "name" => $this->inputDto->name,
    "email" => $this->inputDto->email,
    "cpf" => $this->inputDto->cpf
  ])->mapToDomain();

  $this->userRepositoryMock->shouldReceive('findByEmail')->once()->andReturn(null);
  $this->userRepositoryMock->shouldReceive('findByCPF')->once()->andReturn(null);
  $this->hashGeneratorMock->shouldReceive('generate')->once()->andReturn('hashedPassword');
  $this->accessTokenRepositoryMock->shouldReceive('find')->once()->andReturn(null);
  $this->emailSenderMock->shouldReceive('sendMail')->once();
  $this->accessTokenRepositoryMock->shouldReceive('create');
  
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