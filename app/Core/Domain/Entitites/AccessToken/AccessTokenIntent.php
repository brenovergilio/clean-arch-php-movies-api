<?php

namespace App\Core\Domain\Entities\AccessToken;

enum AccessTokenIntent {
  case CONFIRM_EMAIL;
  case RECOVER_PASSWORD;
}