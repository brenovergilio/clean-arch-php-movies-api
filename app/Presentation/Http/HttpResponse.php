<?php

namespace App\Presentation\Http;
use App\Presentation\Http\HttpStatusCodes;

class HttpResponse {
  public function __construct(
    public mixed $body,
    public HttpStatusCodes $statusCode
  ) {}
}