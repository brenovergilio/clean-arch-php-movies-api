<?php

namespace App\Core\Domain\Entities\AccessToken;

enum AccessTokenIntent: string {
  case CONFIRM_EMAIL = 'confirm-email';
  case RECOVER_PASSWORD = 'recover-password';
}