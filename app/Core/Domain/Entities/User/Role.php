<?php

namespace App\Core\Domain\Entities\User;

enum Role: string {
  case ADMIN = 'admin';
  case CLIENT = 'client';
}