<?php

namespace App\Core\Domain\Entities\User;

enum Role {
  case ADMIN;
  case CLIENT;
}