<?php

namespace App\Infra\Database\ConcreteRepositories;
use App\Core\Domain\Entities\AccessToken\AccessToken;
use App\Core\Domain\Entities\AccessToken\AccessTokenRepository;
use App\Models\AccessTokenModel;

class EloquentAccessTokenRepository implements AccessTokenRepository {
  public function create(AccessToken $accessToken): void {
    $eloquentAccessToken = new AccessTokenModel;
    
    $eloquentAccessToken->intent = AccessTokenModel::mapIntentToModel($accessToken->getIntent());
    $eloquentAccessToken->time_to_leave = $accessToken->getTimeToLeave();
    $eloquentAccessToken->user_id = $accessToken->getRelatedUserID();
    $eloquentAccessToken->token = $accessToken->getToken();

    $eloquentAccessToken->save();
  }

  public function find(string $token): ?AccessToken {
    $eloquentAccessToken = AccessTokenModel::find($token);

    if(!$eloquentAccessToken) return null;

    return $eloquentAccessToken->mapToDomain();
  }

  public function delete(string $token): void {
    AccessTokenModel::destroy($token);
  }
}