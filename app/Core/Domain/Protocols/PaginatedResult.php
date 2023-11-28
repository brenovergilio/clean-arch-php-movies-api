<?php

namespace App\Core\Domain\Protocols;

class PaginatedResult {
  public function __construct(
    public array $data,
    public PaginationProps $paginationProps
  ) {}
}