<?php

namespace App\Core\Domain\Protocols;

class PaginatedResult {
  public function __construct(
    public mixed $data,
    public PaginationProps $paginationProps
  ) {}
}