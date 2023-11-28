<?php

namespace App\Presentation\Http\Controllers\Movie;
use App\Core\Application\UseCases\Movie\FindManyMovies\DTO\FindManyMoviesInputDTO;
use App\Core\Application\UseCases\Movie\FindManyMovies\FindManyMoviesUseCase;
use App\Core\Domain\Entities\Movie\FilterMovies;
use App\Core\Domain\Entities\Movie\OrderMovies;
use App\Core\Domain\Protocols\PaginationProps;
use App\Presentation\Http\HttpRequest;
use App\Presentation\Http\HttpStatusCodes;
use App\Presentation\Http\HttpResponse;

class FindManyMoviesController {
  public function __construct(private FindManyMoviesUseCase $useCase) {}

  public function index(HttpRequest $request): HttpResponse {
      $paginationProps = new PaginationProps($request->queryParams["page"], $request->queryParams["perPage"]);
      $filterProps = new FilterMovies($request->queryParams["fieldValue"] ?? null, $request->queryParams["isPublic"] ?? null);
      $orderProps = new OrderMovies([]);
      
      $inputDto = new FindManyMoviesInputDTO(
        $paginationProps,
        $filterProps,
        $orderProps 
      );

      $result = $this->useCase->execute($inputDto);
      return new HttpResponse(["data" => $result], HttpStatusCodes::OK);
    }
  }