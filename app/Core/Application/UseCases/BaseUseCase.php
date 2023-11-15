<?php

namespace App\Core\Application\UseCases;
use App\Core\Application\Exceptions\InsufficientPermissionsException;
use App\Core\Domain\Entities\Role;

abstract class BaseUseCase {
  protected function checkRole(array $allowedRoles, Role $role) {
    if(!in_array($role, $allowedRoles)) throw new InsufficientPermissionsException;
  }
}