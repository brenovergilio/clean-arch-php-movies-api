<?php

namespace App\Presentation\Http\Interfaces;

class HttpRequest {
  public function __construct(
    public mixed $body,
    public mixed $queryParams
  ) {}
}