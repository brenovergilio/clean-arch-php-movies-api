<?php

namespace App\Core\Application\UseCases\Movie\FindManyMovies\DTO;
use App\Core\Domain\Entities\Movie\FilterMovies;
use App\Core\Domain\Entities\Movie\OrderMovies;
use App\Core\Domain\Protocols\PaginationProps;

class FindManyMoviesInputDTO {
  public function __construct(
    public PaginationProps $paginationProps,
    public FilterMovies $filterMoviesProps,
    public OrderMovies $orderMoviesProps
  ) {}
}