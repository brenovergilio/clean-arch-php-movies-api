<?php

namespace App\Presentation\Http\Interfaces;
use App\Presentation\Http\HttpStatusCodes;

class HttpResponse {
  public function __construct(
    public mixed $body,
    public HttpStatusCodes $statusCode
  ) {}
}