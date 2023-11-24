<?php

namespace App\Presentation\Http\Controllers\User;
use App\Core\Application\UseCases\User\FindUser\DTO\FindUserInputDTO;
use App\Core\Application\UseCases\User\FindUser\FindUserUseCase;
use App\Core\Domain\Exceptions\EntityNotFoundException;
use App\Presentation\Http\HttpStatusCodes;
use App\Presentation\Http\HttpResponse;

class FindUserController {
  public function __construct(private FindUserUseCase $useCase) {}
  
  public function show(string|int $id): HttpResponse {
    try {
      $inputDto = new FindUserInputDTO(
        $id
      );

      $result = $this->useCase->execute($inputDto);
      return new HttpResponse(["data" => $result], HttpStatusCodes::OK);
    } catch (EntityNotFoundException $exception) {
      return new HttpResponse(["error" => $exception->getMessage()], HttpStatusCodes::NOT_FOUND);
    }
  }
}