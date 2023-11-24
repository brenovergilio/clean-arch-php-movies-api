<?php

namespace App\Presentation\Http;

enum HttpStatusCodes: int {
  case OK = 200;
  case NO_CONTENT = 204;
  case BAD_REQUEST = 400;
  case UNAUTHORIZED = 401;
  case FORBIDDEN = 403;
  case NOT_FOUND = 404;
  case METHOD_NOT_ALLOWED = 405;
  case CONFLICT = 409;
  case UNPROCESSABLE_REQUEST = 422;
  case INTERNAL_SERVER_ERROR = 500;
}