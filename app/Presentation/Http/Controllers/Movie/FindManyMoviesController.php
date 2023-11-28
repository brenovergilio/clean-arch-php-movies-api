<?php

namespace App\Presentation\Http\Controllers\Movie;
use App\Core\Application\UseCases\Movie\FindManyMovies\DTO\FindManyMoviesInputDTO;
use App\Core\Application\UseCases\Movie\FindManyMovies\FindManyMoviesUseCase;
use App\Core\Domain\Entities\Movie\FilterMovies;
use App\Core\Domain\Entities\Movie\OrderMovies;
use App\Core\Domain\Exceptions\InvalidFieldException;
use App\Core\Domain\Exceptions\MissingRequiredFieldException;
use App\Core\Domain\Helpers;
use App\Core\Domain\Protocols\OrderDirection;
use App\Core\Domain\Protocols\PaginationProps;
use App\Core\Domain\Protocols\OrderByProps;
use App\Presentation\Http\HttpRequest;
use App\Presentation\Http\HttpStatusCodes;
use App\Presentation\Http\HttpResponse;
use App\Presentation\Validations\Exceptions\InvalidOrderInputException;

class FindManyMoviesController {
  public function __construct(private FindManyMoviesUseCase $useCase) {}

  public function index(HttpRequest $request): HttpResponse {
      try {
        $validationException = MovieControllerValidations::findManyMoviesValidations(array_keys($request->queryParams))->validate($request->queryParams);

        if($validationException) throw $validationException;

        $isPublicFilter = null;
        if(isset($request->queryParams["isPublic"])) {
          $isPublicFilter = Helpers::convertStringBoolToPrimitive($request->queryParams["isPublic"]);
        }

        $paginationProps = new PaginationProps(intval($request->queryParams["page"]), intval($request->queryParams["perPage"]));
        $orderProps = new OrderMovies($this->generateOrderProps($request->queryParams["ordering"] ?? null));
        $filterProps = new FilterMovies($request->queryParams["fieldValue"] ?? null, $isPublicFilter);
        
        $inputDto = new FindManyMoviesInputDTO(
          $paginationProps,
          $filterProps,
          $orderProps 
        );

        $result = $this->useCase->execute($inputDto);
        return new HttpResponse(["data" => $result], HttpStatusCodes::OK);
      } catch (InvalidOrderInputException $exception) {
        return new HttpResponse(["error" => $exception->getMessage()], HttpStatusCodes::BAD_REQUEST); 
      } catch (InvalidFieldException $exception) {
        return new HttpResponse(["error" => $exception->getMessage()], HttpStatusCodes::BAD_REQUEST); 
      } catch (MissingRequiredFieldException $exception) {
        return new HttpResponse(["error" => $exception->getMessage()], HttpStatusCodes::BAD_REQUEST); 
      }
    }
    
    /**
    * @return OrderByProps[]
    */
    private function generateOrderProps(?string $input): array {
      if(!$input || strlen($input) === 0) return [];

      $fields = explode(",", $input);

      $orderByProps = [];

      foreach($fields as $field) {
        if($field[0] === "-") {
          $orderByProps[] = new OrderByProps(substr($field, 1), OrderDirection::DESC);
        }  else {
          $orderByProps[] = new OrderByProps($field, OrderDirection::ASC);
        }
      }

      return $orderByProps;
    }
  }