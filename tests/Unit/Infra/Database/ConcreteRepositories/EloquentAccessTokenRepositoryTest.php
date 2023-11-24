<?php
use App\Infra\Database\ConcreteRepositories\EloquentAccessTokenRepository;
use App\Models\AccessTokenModel;
use App\Models\UserModel;

beforeEach(function() {
  $this->sut = new EloquentAccessTokenRepository();
});

it("should return null when calling find because access token does not exist", function() {
  expect($this->sut->find('token'))->toBe(null);
});

// it("should return access token when calling find because access token does exist", function() {
//   $accessToken = AccessTokenModel::factory()->createOne([
//     "token" => "A12B4X"
//   ]);
//   info(json_encode(AccessTokenModel::all()));
//   $result = $this->sut->find($accessToken->token);

//   expect($accessToken->token)->toBe($result->getToken());
//   expect($accessToken->user_id)->toBe($result->getRelatedUserID());
//   expect($accessToken->intent)->toBe(AccessTokenModel::mapIntentToModel($result->getIntent()));
//   expect($accessToken->time_to_leave)->toBe($result->getTimeToLeave());
// });

// it("should delete access token", function() {
//   $accessToken = AccessTokenModel::factory()->createOne();
//   expect(AccessTokenModel::find($accessToken->token))->toBeTruthy();

//   $this->sut->delete($accessToken->token);
//   expect(AccessTokenModel::find($accessToken->token))->toBeNull();
// });

// it("should create an access token", function() {
//   $user = UserModel::factory()->createOne();
//   $accessToken = AccessTokenModel::factory()->makeOne([
//     "token" => "A1B2C3",
//     "user_id" => $user->id,
//     "time_to_leave" => 60*60,
//     "intent" => "confirm-email",
//     "created_at" => new DateTime()
//   ])->mapToDomain();
  
//   $this->sut->create($accessToken);

//   $result = AccessTokenModel::find("A1B2C3");

//   expect($result)->not->toBeNull();
//   expect($result->user_id)->toBe($user->id);
//   expect($result->time_to_leave)->toBe(60*60);
//   expect($result->intent)->toBe("confirm-email");
// });