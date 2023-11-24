<?php

namespace App\Infra\Storage;
use App\Core\Application\Interfaces\Folders;
use App\Core\Application\Interfaces\UploadableFile;
use Illuminate\Http\UploadedFile;

class LaravelUploadableFile implements UploadableFile {
  public function __construct(private UploadedFile $file) {}

  public function upload(Folders $folder): string {
    return $this->file->store($folder->value);
  }
}