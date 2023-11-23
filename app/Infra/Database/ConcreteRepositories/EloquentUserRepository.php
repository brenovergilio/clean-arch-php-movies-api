<?php

namespace App\Infra\Database\ConcreteRepositories;
use App\Core\Domain\Entities\User\User;
use App\Core\Domain\Entities\User\UserRepository;
use App\Models\UserModel;

class EloquentUserRepository implements UserRepository {
  public function create(User $user, bool $returning = false): ?User {
    $eloquentUser = new UserModel;
    $eloquentUser->mergeDomain($user);
    $eloquentUser->save();

    if($returning) {
      $eloquentUser->refresh();
      return $eloquentUser->mapToDomain();
    }
  }

  public function update(User $user, bool $returning = false): ?User {
    $eloquentUser = UserModel::find($user->id());
    $eloquentUser->mergeDomain($user);
    $eloquentUser->save();

    if($returning) {
      return $user;
    }
  }

  public function findByID(string|int $id): ?User {
    $eloquentUser = UserModel::find($id);

    if(!$eloquentUser) return null;

    return $eloquentUser->mapToDomain();
  }

  public function findByEmail(string $email): ?User {
    $eloquentUser = UserModel::where('email', $email)->first();

    if(!$eloquentUser) return null;

    return $eloquentUser->mapToDomain();
  }

  public function findByCPF(string $cpf): ?User {
    $eloquentUser = UserModel::where('cpf', $cpf)->first();

    if(!$eloquentUser) return null;

    return $eloquentUser->mapToDomain();
  }
}