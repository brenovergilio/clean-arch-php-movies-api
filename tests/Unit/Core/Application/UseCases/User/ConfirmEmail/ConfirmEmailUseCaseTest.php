<?php

use App\Core\Application\UseCases\User\ConfirmEmail\ConfirmEmailUseCase;
use App\Core\Application\UseCases\User\ConfirmEmail\DTO\ConfirmEmailInputDTO;
use App\Core\Domain\Entities\AccessToken\AccessTokenIntent;
use App\Core\Domain\Entities\AccessToken\AccessTokenRepository;
use App\Core\Domain\Entities\User\UserRepository;
use App\Models\AccessTokenModel;
use App\Models\UserModel;

beforeEach(function() {
  $this->userRepositoryMock = Mockery::mock(UserRepository::class);
  $this->accessTokenRepositoryMock = Mockery::mock(AccessTokenRepository::class);

  $this->inputDto = new ConfirmEmailInputDTO("123456");
  $this->user = UserModel::factory()->client()->makeOne([
    "id" => 1
  ])->mapToDomain();

  $this->sut = new ConfirmEmailUseCase(
    $this->userRepositoryMock,
    $this->accessTokenRepositoryMock
  );
});

afterEach(function() {
  Mockery::close();
});


it('should throw an EntityNotFoundException because token was not found', function() {
  $this->accessTokenRepositoryMock->shouldReceive('find')->andReturn(null);
  
  expect(function(){
    $this->sut->execute($this->inputDto);
  })->toThrow("Entity AccessToken not found");
});

it('should throw an EntityNotFoundException because token intent is different from CONFIRM_EMAIL intent', function() {
  $accessToken = AccessTokenModel::factory()->makeOne([
    "intent" => AccessTokenModel::mapIntentToModel(AccessTokenIntent::RECOVER_PASSWORD),
    "user_id" => $this->user->id(),
    "time_to_leave" => 10,
    "created_at" => new DateTime(),
    "token" => null
  ])->mapToDomain();

  $this->accessTokenRepositoryMock->shouldReceive('find')->andReturn($accessToken);
  
  expect(function(){
    $this->sut->execute($this->inputDto);
  })->toThrow("Entity AccessToken not found");
});

it('should throw an ExpiredAccessTokenException because token is expired', function() {
  $now = new DateTime();
  $oneHourAgo = $now->modify('-1 hour');

  $accessToken = AccessTokenModel::factory()->makeOne([
    "intent" => AccessTokenModel::mapIntentToModel(AccessTokenIntent::CONFIRM_EMAIL),
    "user_id" => $this->user->id(),
    "time_to_leave" => 0,
    "created_at" => $oneHourAgo,
    "token" => null
  ])->mapToDomain();

  $this->accessTokenRepositoryMock->shouldReceive('find')->andReturn($accessToken);
  
  expect(function(){
    $this->sut->execute($this->inputDto);
  })->toThrow("Access Token is expired");
});

it('should throw an EntityNotFoundException because user was not found', function() {
  $accessToken = AccessTokenModel::factory()->makeOne([
    "intent" => AccessTokenModel::mapIntentToModel(AccessTokenIntent::CONFIRM_EMAIL),
    "user_id" => $this->user->id(),
    "time_to_leave" => 10,
    "created_at" => new DateTime(),
    "token" => null
  ])->mapToDomain();

  $this->accessTokenRepositoryMock->shouldReceive('find')->andReturn($accessToken);
  $this->userRepositoryMock->shouldReceive('findByID')->andReturn(null);

  expect(function(){
    $this->sut->execute($this->inputDto);
  })->toThrow("Entity User not found");
});

it('should call update() with right values', function() {
  $accessToken = AccessTokenModel::factory()->makeOne([
    "intent" => AccessTokenModel::mapIntentToModel(AccessTokenIntent::CONFIRM_EMAIL),
    "user_id" => $this->user->id(),
    "time_to_leave" => 10,
    "created_at" => new DateTime(),
    "token" => null
  ])->mapToDomain();

  $this->accessTokenRepositoryMock->shouldReceive('find')->andReturn($accessToken);
  $this->userRepositoryMock->shouldReceive('findByID')->andReturn($this->user);
  $this->accessTokenRepositoryMock->shouldReceive('delete');

  $this->userRepositoryMock->shouldReceive('update')->with(Mockery::on(function ($argument) {
    return $argument->isEmailConfirmed();
  }))->once();

  $this->sut->execute($this->inputDto);
});

it('should call delete() with right value', function() {
 $accessToken = AccessTokenModel::factory()->makeOne([
    "intent" => AccessTokenModel::mapIntentToModel(AccessTokenIntent::CONFIRM_EMAIL),
    "user_id" => $this->user->id(),
    "time_to_leave" => 10,
    "created_at" => new DateTime(),
    "token" => null
  ])->mapToDomain();

  $this->accessTokenRepositoryMock->shouldReceive('find')->andReturn($accessToken);
  $this->userRepositoryMock->shouldReceive('findByID')->andReturn($this->user);
  $this->userRepositoryMock->shouldReceive('update');
  
  $this->accessTokenRepositoryMock->shouldReceive('delete')->with($accessToken->getToken())->once();

  $this->sut->execute($this->inputDto);
});