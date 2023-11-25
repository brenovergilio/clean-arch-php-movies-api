<?php

namespace App\Infra\Handlers;
use App\Core\Application\Interfaces\FileManipulator;
use Illuminate\Support\Facades\Storage;

class LaravelFileHandler implements FileManipulator {
  public function exists(string $path): bool {
    return Storage::exists($path);
  }

  public function delete(string $path): bool {
    return Storage::delete($path);
  }
}