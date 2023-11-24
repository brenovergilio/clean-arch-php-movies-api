<?php
use App\Core\Application\Interfaces\EmailSender;
use App\Core\Application\Interfaces\Folders;
use App\Core\Application\Interfaces\UploadableFile;
use App\Core\Application\UseCases\User\UpdateUser\DTO\UpdateUserInputDTO;
use App\Core\Application\UseCases\User\UpdateUser\UpdateUserUseCase;
use App\Core\Domain\Entities\AccessToken\AccessTokenRepository;
use App\Core\Domain\Entities\User\Role;
use App\Core\Domain\Entities\User\User;
use App\Core\Domain\Entities\User\UserRepository;
use App\Models\UserModel;

beforeEach(function() {
  $this->userRepositoryMock = Mockery::mock(UserRepository::class);
  $this->accessTokenRepositoryMock = Mockery::mock(AccessTokenRepository::class);
  $this->emailSenderMock = Mockery::mock(EmailSender::class);
  $this->loggedUser = UserModel::factory()->client()->makeOne([
    "id" => 1
  ])->mapToDomain();
  $this->inputDto = new UpdateUserInputDTO(
    $this->loggedUser->id(),
    'Another Name',
    'anotherValid@mail.com',
    '51115471007',
    null
  );

  $this->sut = new UpdateUserUseCase(
    $this->userRepositoryMock,
    $this->accessTokenRepositoryMock,
    $this->emailSenderMock,
    $this->loggedUser,
  );
});

afterEach(function() {
  Mockery::close();
});


it('should throw an InsufficientPermissionsException because logged user ID is different from input DTO ID', function() {
  $this->inputDto->id = "anotherId";

  expect(function() {
    $this->sut->execute($this->inputDto);
  })->toThrow("Insufficient Permissions");
});

it('should throw an DuplicatedUniqueFieldException because there is another user with the email provided', function() {
  $existingUser = UserModel::factory()->client()->makeOne([
    "id" => 2
  ])->mapToDomain();
  $this->userRepositoryMock->shouldReceive('findByID')->andReturn($this->loggedUser);  
  $this->userRepositoryMock->shouldReceive('findByEmail')->andReturn($existingUser);

  expect(function() {
    $this->sut->execute($this->inputDto);
  })->toThrow("Field Email is already in use");
});

it('should throw an DuplicatedUniqueFieldException because there is another user with the CPF provided', function() {
  $existingUser = UserModel::factory()->client()->makeOne([
    "id" => 2
  ])->mapToDomain();  $this->userRepositoryMock->shouldReceive('findByID')->andReturn($this->loggedUser);  
  $this->userRepositoryMock->shouldReceive('findByEmail')->andReturn(null);
  $this->userRepositoryMock->shouldReceive('findByCPF')->andReturn($existingUser);

  expect(function() {
    $this->sut->execute($this->inputDto);
  })->toThrow("Field CPF is already in use");
});

it('should not throw a DuplicatedUniqueFieldException because the email provided belongs to the logged user', function() {
  $this->userRepositoryMock->shouldReceive('findByID')->andReturn($this->loggedUser);  
  $this->userRepositoryMock->shouldReceive('findByEmail')->andReturn($this->loggedUser);
  $this->userRepositoryMock->shouldReceive('findByCPF')->andReturn(null);
  $this->accessTokenRepositoryMock->shouldReceive('find');
  $this->emailSenderMock->shouldReceive('sendMail');
  $this->accessTokenRepositoryMock->shouldReceive('create');

  $this->userRepositoryMock->shouldReceive('update')->once();

  $this->sut->execute($this->inputDto);
});

it('should not throw a DuplicatedUniqueFieldException because the CPF provided belongs to the logged user', function() {
  $this->userRepositoryMock->shouldReceive('findByID')->andReturn($this->loggedUser);  
  $this->userRepositoryMock->shouldReceive('findByEmail')->andReturn(null);
  $this->userRepositoryMock->shouldReceive('findByCPF')->andReturn($this->loggedUser);
  $this->accessTokenRepositoryMock->shouldReceive('find');
  $this->emailSenderMock->shouldReceive('sendMail');
  $this->accessTokenRepositoryMock->shouldReceive('create');

  $this->userRepositoryMock->shouldReceive('update')->once();

  $this->sut->execute($this->inputDto);
});

it('should not call findByCPF because there is no CPF provided', function() {
  $this->userRepositoryMock->shouldReceive('findByID')->andReturn($this->loggedUser);  
  $this->userRepositoryMock->shouldReceive('findByEmail')->andReturn(null);
  $this->accessTokenRepositoryMock->shouldReceive('find');
  $this->emailSenderMock->shouldReceive('sendMail');
  $this->userRepositoryMock->shouldReceive('update');
  $this->accessTokenRepositoryMock->shouldReceive('create');
  $this->userRepositoryMock->shouldNotHaveReceived('findByCPF');

  $this->inputDto->cpf = null;
  $this->sut->execute($this->inputDto);
});

it('should not call findByEmail because there is no email provided', function() {
  $this->userRepositoryMock->shouldReceive('findByID')->andReturn($this->loggedUser);  
  $this->userRepositoryMock->shouldReceive('findByCPF')->andReturn(null);
  $this->accessTokenRepositoryMock->shouldReceive('find');
  $this->emailSenderMock->shouldReceive('sendMail');
  $this->userRepositoryMock->shouldReceive('update');
  $this->accessTokenRepositoryMock->shouldReceive('create');
  $this->userRepositoryMock->shouldNotHaveReceived('findByEmail');

  $this->inputDto->email = null;
  $this->sut->execute($this->inputDto);
});

it('should call upload() method with right folder value if a photo is provided', function() {
  $this->userRepositoryMock->shouldReceive('findByID')->andReturn($this->loggedUser);  
  $this->userRepositoryMock->shouldReceive('findByEmail')->andReturn(null);
  $this->userRepositoryMock->shouldReceive('findByCPF')->andReturn(null);
  $this->accessTokenRepositoryMock->shouldReceive('find');
  $this->emailSenderMock->shouldReceive('sendMail');
  $this->accessTokenRepositoryMock->shouldReceive('create');

  $uploadableFile = Mockery::mock(UploadableFile::class);
  $uploadableFile->shouldReceive('upload')->with(Folders::USERS)->once();
 
  $this->inputDto->photo = $uploadableFile;
  $this->userRepositoryMock->shouldReceive('update');

  $this->sut->execute($this->inputDto);
});

it('should call update() with the right values', function() {
  $updatedUser = new User($this->loggedUser->id(), $this->inputDto->name, $this->inputDto->cpf, $this->inputDto->email, $this->loggedUser->password(), Role::CLIENT, $this->loggedUser->photo(), true);
  $updatedUser = UserModel::factory()->client()->makeOne([
    "id" => $this->loggedUser->id(),
    "name" => $this->inputDto->name,
    "cpf" => $this->inputDto->cpf,
    "email" => $this->inputDto->email,
    "password" => $this->loggedUser->password(),
    "photo" =>  $this->loggedUser->photo(), 
    "email_confirmed" => true
  ])->mapToDomain();

  $this->userRepositoryMock->shouldReceive('findByID')->andReturn($this->loggedUser);  
  $this->userRepositoryMock->shouldReceive('findByEmail')->andReturn(null);
  $this->userRepositoryMock->shouldReceive('findByCPF')->andReturn(null);
  $this->accessTokenRepositoryMock->shouldReceive('find');
  $this->emailSenderMock->shouldReceive('sendMail');
  $this->accessTokenRepositoryMock->shouldReceive('create');

  $this->userRepositoryMock->shouldReceive('update')->with(Mockery::on(function ($argument) use ($updatedUser) {
    return $argument->id() === $updatedUser->id() &&
           $argument->name() === $updatedUser->name() &&
           $argument->cpf() === $updatedUser->cpf() &&
           $argument->email() === $updatedUser->email() &&
           $argument->password() === $updatedUser->password() &&
           $argument->photo() === $updatedUser->photo();
  }))->once();

  $this->sut->execute($this->inputDto);
});