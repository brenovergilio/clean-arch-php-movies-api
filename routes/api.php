<?php

use App\Infra\Factories\UseCases\Auth\LoginUseCaseFactory;
use App\Infra\Factories\UseCases\User\ConfirmEmailUseCaseFactory;
use App\Infra\Factories\UseCases\User\CreateUserUseCaseFactory;
use App\Infra\Factories\UseCases\User\FindUserUseCaseFactory;
use App\Infra\Storage\LaravelUploadableFile;
use App\Presentation\Http\Controllers\Auth\LoginController;
use App\Presentation\Http\Controllers\User\ConfirmEmailUserController;
use App\Presentation\Http\Controllers\User\FindUserController;
use App\Presentation\Http\Controllers\User\StoreUserController;
use App\Presentation\Http\HttpRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


//Login
Route::post('/login', function (Request $request) {
    $loginUseCase = LoginUseCaseFactory::make();
    $controller = new LoginController($loginUseCase);

    $httpRequest = new HttpRequest($request->all());
    $result = $controller->login($httpRequest);

    return response()->json($result->body, $result->statusCode->value);
});

//User
Route::get('/user/{id}', function (string|int $id) {
    $findUserUseCase = FindUserUseCaseFactory::make();
    $controller = new FindUserController($findUserUseCase);

    $result = $controller->show($id);

    return response()->json($result->body, $result->statusCode->value);
});

Route::post('/user', function (Request $request) {
    $createUserUseCase = CreateUserUseCaseFactory::make();
    $controller = new StoreUserController($createUserUseCase);

    $httpRequest = new HttpRequest($request->all());
    if($request->hasFile("photo")) $httpRequest->body["photo"] = new LaravelUploadableFile($request->file("photo"));
    
    $result = $controller->store($httpRequest);
    
    return response()->json($result->body, $result->statusCode->value);
});

Route::patch('/user/confirm-email', function (Request $request) {
    $loggedUser = auth()->user()->mapToDomain();
    $confirmEmailUseCase = ConfirmEmailUseCaseFactory::make($loggedUser);
    $controller = new ConfirmEmailUserController($confirmEmailUseCase);

    $httpRequest = new HttpRequest($request->all());
    $result = $controller->confirmEmail($httpRequest);
    
    return response()->json($result->body, $result->statusCode->value);
})->middleware('jwt.auth');