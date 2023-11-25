<?php

namespace App\Core\Domain\Protocols;

class PaginatedReturn {
  public function __construct(
    public mixed $data,
    public PaginationProps $paginationProps
  ) {}
}