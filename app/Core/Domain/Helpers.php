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

}