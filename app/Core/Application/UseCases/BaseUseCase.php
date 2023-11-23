<?php

namespace App\Core\Application\UseCases;
use App\Core\Application\Exceptions\InsufficientPermissionsException;
use App\Core\Domain\Entities\User\Role;
use App\Core\Domain\Entities\User\User;

abstract class BaseUseCase {
  public function checkAdmin(User $user): void {
    if(!$user->isAdmin()) throw new InsufficientPermissionsException;
  }

  public function checkSameUser(User $user, string|int $id): void {
    if($user->id() !== $id) throw new InsufficientPermissionsException;
  }

  public function checkEmailConfirmed(User $user): void {
    if(!$user->isEmailConfirmed()) throw new InsufficientPermissionsException;
  }
}