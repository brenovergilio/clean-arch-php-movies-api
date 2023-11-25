<?php

use App\Infra\Factories\UseCases\Auth\LoginUseCaseFactory;
use App\Infra\Factories\UseCases\Movie\CreateMovieUseCaseFactory;
use App\Infra\Factories\UseCases\Movie\DeleteMovieUseCaseFactory;
use App\Infra\Factories\UseCases\Movie\FindMovieUseCaseFactory;
use App\Infra\Factories\UseCases\Movie\UpdateMovieUseCaseFactory;
use App\Infra\Factories\UseCases\User\ChangePasswordUseCaseFactory;
use App\Infra\Factories\UseCases\User\ConfirmEmailUseCaseFactory;
use App\Infra\Factories\UseCases\User\CreateUserUseCaseFactory;
use App\Infra\Factories\UseCases\User\FindUserUseCaseFactory;
use App\Infra\Factories\UseCases\User\UpdateUserUseCaseFactory;
use App\Infra\Storage\LaravelUploadableFile;
use App\Presentation\Http\Controllers\Auth\LoginController;
use App\Presentation\Http\Controllers\Movie\DeleteMovieController;
use App\Presentation\Http\Controllers\Movie\FindMovieController;
use App\Presentation\Http\Controllers\Movie\StoreMovieController;
use App\Presentation\Http\Controllers\Movie\UpdateMovieController;
use App\Presentation\Http\Controllers\User\ChangePasswordUserController;
use App\Presentation\Http\Controllers\User\ConfirmEmailUserController;
use App\Presentation\Http\Controllers\User\FindUserController;
use App\Presentation\Http\Controllers\User\StoreUserController;
use App\Presentation\Http\Controllers\User\UpdateUserController;
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
    $confirmEmailUseCase = ConfirmEmailUseCaseFactory::make();
    $controller = new ConfirmEmailUserController($confirmEmailUseCase);

    $httpRequest = new HttpRequest($request->all());
    $result = $controller->confirmEmail($httpRequest);
    
    return response()->json($result->body, $result->statusCode->value);
});

Route::patch('/user/change-password', function (Request $request) {
    $loggedUser = auth()->user()->mapToDomain();
    $changePasswordUseCase = ChangePasswordUseCaseFactory::make($loggedUser);
    $controller = new ChangePasswordUserController($changePasswordUseCase);

    $httpRequest = new HttpRequest($request->all());
    $result = $controller->changePassword($httpRequest);
    
    return response()->json($result->body, $result->statusCode->value);
})->middleware('jwt.auth');

Route::post('/user/update', function (Request $request) {
    $loggedUser = auth()->user()->mapToDomain();
    $updateUserUseCase = UpdateUserUseCaseFactory::make($loggedUser);
    $controller = new UpdateUserController($updateUserUseCase);

    $httpRequest = new HttpRequest($request->all());
    if($request->hasFile("photo")) $httpRequest->body["photo"] = new LaravelUploadableFile($request->file("photo"));

    $result = $controller->update($httpRequest);
    
    return response()->json($result->body, $result->statusCode->value);
})->middleware('jwt.auth');

//Movie
Route::get('/movie/{id}', function (string|int $id) {
    $loggedUser = auth()->user()->mapToDomain();
    $findMovieUseCase = FindMovieUseCaseFactory::make($loggedUser);
    $controller = new FindMovieController($findMovieUseCase);

    $result = $controller->show($id);

    return response()->json($result->body, $result->statusCode->value);
})->middleware('jwt.auth');

Route::delete('/movie/{id}', function (string|int $id) {
    $loggedUser = auth()->user()->mapToDomain();
    $deleteMovieUseCase = DeleteMovieUseCaseFactory::make($loggedUser);
    $controller = new DeleteMovieController($deleteMovieUseCase);

    $result = $controller->delete($id);

    return response()->json($result->body, $result->statusCode->value);
})->middleware('jwt.auth');

Route::post('/movie', function (Request $request) {
    $loggedUser = auth()->user()->mapToDomain();
    $createMovieUseCase = CreateMovieUseCaseFactory::make($loggedUser);
    $controller = new StoreMovieController($createMovieUseCase);

    $httpRequest = new HttpRequest($request->all());

    if($request->hasFile("cover")) $httpRequest->body["cover"] = new LaravelUploadableFile($request->file("cover"));

    $result = $controller->store($httpRequest);

    return response()->json($result->body, $result->statusCode->value);
})->middleware('jwt.auth');

Route::post('/movie/{id}', function (string|int $id, Request $request) {
    $loggedUser = auth()->user()->mapToDomain();
    $updateMovieUseCase = UpdateMovieUseCaseFactory::make($loggedUser);
    $controller = new UpdateMovieController($updateMovieUseCase);

    $httpRequest = new HttpRequest($request->all());

    if($request->hasFile("cover")) $httpRequest->body["cover"] = new LaravelUploadableFile($request->file("cover"));

    $result = $controller->update($id, $httpRequest);

    return response()->json($result->body, $result->statusCode->value);
})->middleware('jwt.auth');