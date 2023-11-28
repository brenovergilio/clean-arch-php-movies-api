<?php

namespace App\Core\Domain;

class Helpers {
  public static function generateRandomString(int $length): string
  {
    if($length < 1) return '';

    $actualLength = ceil($length / 2);
    $randomString = bin2hex(random_bytes($actualLength));

    if ($length % 2 !== 0) {
        $randomString = substr($randomString, 0, -1);
    }

    return $randomString;
  }

  public static function convertStringBoolToPrimitive(string|bool $value): bool {
    if(!is_bool($value)) {
      return $value === "true";
    }
    
    return $value;
  }

  const ONE_HOUR_IN_SECONDS = 60 * 60;
  const ONE_WEEK_IN_SECONDS = 60 * 60 * 24 * 7;
}