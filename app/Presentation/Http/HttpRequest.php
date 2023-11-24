<?php

namespace App\Presentation\Http;

class HttpRequest {
  public function __construct(
    public array $body = [],
    public array $queryParams = []
  ) {}
}