<?php

namespace App\Core\Application\Interfaces;

interface FileManipulator {
  public function exists(string $path): bool;
  public function delete(string $path): bool;
}