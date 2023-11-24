<?php

namespace App\Core\Application\Interfaces;

enum Folders: string {
  case USERS = "users";
  case COVERS = "covers";
}

interface UploadableFile {
  public function upload(Folders $folder): string;
}