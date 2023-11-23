<?php

use App\Core\Application\UseCases\User\ConfirmEmail\ConfirmEmailUseCase;
use App\Core\Application\UseCases\User\ConfirmEmail\DTO\ConfirmEmailInputDTO;
use App\Core\Domain\Entities\AccessToken\AccessToken;
use App\Core\Domain\Entities\AccessToken\AccessTokenIntent;
use App\Core\Domain\Entities\AccessToken\AccessTokenRepository;
use App\Core\Domain\Entities\User\UserRepository;
use DateTime;

beforeEach(function() {
  $this->userRepositoryMock = Mockery::mock(UserRepository::class);
  $this->accessTokenRepositoryMock = Mockery::mock(AccessTokenRepository::class);

  $this->inputDto = new ConfirmEmailInputDTO("123456");
  $this->user = new \App\Core\Domain\Entities\User\User("id", "name", "146.290.370-39", "valid@mail.com", "password", \App\Core\Domain\Entities\User\Role::CLIENT, 'photo', false);

  $this->sut = new ConfirmEmailUseCase(
    $this->userRepositoryMock,
    $this->accessTokenRepositoryMock
  );
});

it('should throw an EntityNotFoundException because token was not found', function() {
  $this->accessTokenRepositoryMock->shouldReceive('find')->andReturn(null);
  
  expect(function(){
    $this->sut->execute($this->inputDto);
  })->toThrow("Entity AccessToken not found");
});

it('should throw an EntityNotFoundException because token intent is different from CONFIRM_EMAIL intent', function() {
  $accessToken = new AccessToken(
    AccessTokenIntent::RECOVER_PASSWORD,
    $this->user->id(),
    10,
    new DateTime(),
    null
  );
  $this->accessTokenRepositoryMock->shouldReceive('find')->andReturn($accessToken);
  
  expect(function(){
    $this->sut->execute($this->inputDto);
  })->toThrow("Entity AccessToken not found");
});

it('should throw an ExpiredAccessTokenException because token is expired', function() {
  $now = new DateTime();
  $oneHourAgo = $now->modify('-1 hour');

  $accessToken = new AccessToken(
    AccessTokenIntent::CONFIRM_EMAIL,
    $this->user->id(),
    0,
    $oneHourAgo,
    null
  );
  $this->accessTokenRepositoryMock->shouldReceive('find')->andReturn($accessToken);
  
  expect(function(){
    $this->sut->execute($this->inputDto);
  })->toThrow("Access Token is expired");
});

it('should throw an EntityNotFoundException because user was not found', function() {
  $accessToken = new AccessToken(
    AccessTokenIntent::CONFIRM_EMAIL,
    $this->user->id(),
    10,
    new DateTime(),
    null
  );
  $this->accessTokenRepositoryMock->shouldReceive('find')->andReturn($accessToken);
  $this->userRepositoryMock->shouldReceive('findByID')->andReturn(null);

  expect(function(){
    $this->sut->execute($this->inputDto);
  })->toThrow("Entity User not found");
});

it('should call update() with right values', function() {
  $accessToken = new AccessToken(
    AccessTokenIntent::CONFIRM_EMAIL,
    $this->user->id(),
    10,
    new DateTime(),
    null
  );
  $this->accessTokenRepositoryMock->shouldReceive('find')->andReturn($accessToken);
  $this->userRepositoryMock->shouldReceive('findByID')->andReturn($this->user);
  $this->accessTokenRepositoryMock->shouldReceive('delete');

  $this->userRepositoryMock->shouldReceive('update')->with(Mockery::on(function ($argument) {
    return $argument->isEmailConfirmed();
  }))->once();

  $this->sut->execute($this->inputDto);
});

it('should call delete() with right value', function() {
  $accessToken = new AccessToken(
    AccessTokenIntent::CONFIRM_EMAIL,
    $this->user->id(),
    10,
    new DateTime(),
    null
  );
  $this->accessTokenRepositoryMock->shouldReceive('find')->andReturn($accessToken);
  $this->userRepositoryMock->shouldReceive('findByID')->andReturn($this->user);
  $this->userRepositoryMock->shouldReceive('update');
  
  $this->accessTokenRepositoryMock->shouldReceive('delete')->with($accessToken->getToken())->once();

  $this->sut->execute($this->inputDto);
});