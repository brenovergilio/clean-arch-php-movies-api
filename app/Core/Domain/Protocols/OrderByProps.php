<?php

namespace App\Core\Domain\Protocols;

enum OrderDirection: string {
  case ASC = "asc";
  case DESC = "desc";
}

class OrderByProps {
  public function __construct(
    public ?string $fieldName,
    public OrderDirection $direction
  ) {}
}