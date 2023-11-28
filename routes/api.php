<?php

use App\Infra\Factories\UseCases\Auth\LoginUseCaseFactory;
use App\Infra\Factories\UseCases\Movie\CreateMovieUseCaseFactory;
use App\Infra\Factories\UseCases\Movie\DeleteMovieUseCaseFactory;
use App\Infra\Factories\UseCases\Movie\FindManyMoviesUseCaseFactory;
use App\Infra\Factories\UseCases\Movie\FindMovieUseCaseFactory;
use App\Infra\Factories\UseCases\Movie\UpdateMovieUseCaseFactory;
use App\Infra\Factories\UseCases\User\ChangePasswordUseCaseFactory;
use App\Infra\Factories\UseCases\User\ConfirmEmailUseCaseFactory;
use App\Infra\Factories\UseCases\User\CreateUserUseCaseFactory;
use App\Infra\Factories\UseCases\User\FindUserUseCaseFactory;
use App\Infra\Factories\UseCases\User\UpdateUserUseCaseFactory;
use App\Infra\Storage\LaravelUploadableFile;
use App\Models\MovieModel;
use App\Models\UserModel;
use App\Presentation\Http\Controllers\Auth\LoginController;
use App\Presentation\Http\Controllers\Movie\DeleteMovieController;
use App\Presentation\Http\Controllers\Movie\FindManyMoviesController;
use App\Presentation\Http\Controllers\Movie\FindMovieController;
use App\Presentation\Http\Controllers\Movie\StoreMovieController;
use App\Presentation\Http\Controllers\Movie\UpdateMovieController;
use App\Presentation\Http\Controllers\User\ChangePasswordUserController;
use App\Presentation\Http\Controllers\User\ConfirmEmailUserController;
use App\Presentation\Http\Controllers\User\FindUserController;
use App\Presentation\Http\Controllers\User\StoreUserController;
use App\Presentation\Http\Controllers\User\UpdateUserController;
use App\Presentation\Http\HttpRequest;
use App\Presentation\Http\HttpStatusCodes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

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
Route::get('/users/{id}', function (string|int $id) {
    $findUserUseCase = FindUserUseCaseFactory::make();
    $controller = new FindUserController($findUserUseCase);

    $result = $controller->show($id);

    return response()->json($result->body, $result->statusCode->value);
});

Route::post('/users', function (Request $request) {
    $createUserUseCase = CreateUserUseCaseFactory::make();
    $controller = new StoreUserController($createUserUseCase);

    $httpRequest = new HttpRequest($request->all());
    if($request->hasFile("photo")) $httpRequest->body["photo"] = new LaravelUploadableFile($request->file("photo"));
    
    $result = $controller->store($httpRequest);
    
    return response()->json($result->body, $result->statusCode->value);
});

Route::patch('/users/confirm-email', function (Request $request) {
    $confirmEmailUseCase = ConfirmEmailUseCaseFactory::make();
    $controller = new ConfirmEmailUserController($confirmEmailUseCase);

    $httpRequest = new HttpRequest($request->all());
    $result = $controller->confirmEmail($httpRequest);
    
    return response()->json($result->body, $result->statusCode->value);
});

Route::patch('/users/change-password', function (Request $request) {
    $loggedUser = auth()->user()->mapToDomain();
    $changePasswordUseCase = ChangePasswordUseCaseFactory::make($loggedUser);
    $controller = new ChangePasswordUserController($changePasswordUseCase);

    $httpRequest = new HttpRequest($request->all());
    $result = $controller->changePassword($httpRequest);
    
    return response()->json($result->body, $result->statusCode->value);
})->middleware('jwt.auth');

Route::post('/users/update', function (Request $request) {
    $loggedUser = auth()->user()->mapToDomain();
    $updateUserUseCase = UpdateUserUseCaseFactory::make($loggedUser);
    $controller = new UpdateUserController($updateUserUseCase);

    $httpRequest = new HttpRequest($request->all());
    if($request->hasFile("photo")) $httpRequest->body["photo"] = new LaravelUploadableFile($request->file("photo"));

    $result = $controller->update($httpRequest);
    
    return response()->json($result->body, $result->statusCode->value);
})->middleware('jwt.auth');

//Movie
Route::get('/movies/{id}', function (string|int $id) {
    $loggedUser = auth()->user()->mapToDomain();
    $findMovieUseCase = FindMovieUseCaseFactory::make($loggedUser);
    $controller = new FindMovieController($findMovieUseCase);

    $result = $controller->show($id);

    return response()->json($result->body, $result->statusCode->value);
})->middleware('jwt.auth');

Route::get('/movies', function (Request $request) {
    $loggedUser = auth()->user()->mapToDomain();
    $findManyMoviesUseCase = FindManyMoviesUseCaseFactory::make($loggedUser);
    $controller = new FindManyMoviesController($findManyMoviesUseCase);
    $httpRequest = new HttpRequest([], $request->query());

    $result = $controller->index($httpRequest);

    return response()->json($result->body, $result->statusCode->value);
})->middleware("jwt.auth");

Route::delete('/movies/{id}', function (string|int $id) {
    $loggedUser = auth()->user()->mapToDomain();
    $deleteMovieUseCase = DeleteMovieUseCaseFactory::make($loggedUser);
    $controller = new DeleteMovieController($deleteMovieUseCase);

    $result = $controller->delete($id);

    return response()->json($result->body, $result->statusCode->value);
})->middleware('jwt.auth');

Route::post('/movies', function (Request $request) {
    $loggedUser = auth()->user()->mapToDomain();
    $createMovieUseCase = CreateMovieUseCaseFactory::make($loggedUser);
    $controller = new StoreMovieController($createMovieUseCase);

    $httpRequest = new HttpRequest($request->all());

    if($request->hasFile("cover")) $httpRequest->body["cover"] = new LaravelUploadableFile($request->file("cover"));

    $result = $controller->store($httpRequest);

    return response()->json($result->body, $result->statusCode->value);
})->middleware('jwt.auth');

Route::post('/movies/{id}', function (string|int $id, Request $request) {
    $loggedUser = auth()->user()->mapToDomain();
    $updateMovieUseCase = UpdateMovieUseCaseFactory::make($loggedUser);
    $controller = new UpdateMovieController($updateMovieUseCase);

    $httpRequest = new HttpRequest($request->all());

    if($request->hasFile("cover")) $httpRequest->body["cover"] = new LaravelUploadableFile($request->file("cover"));

    $result = $controller->update($id, $httpRequest);

    return response()->json($result->body, $result->statusCode->value);
})->middleware('jwt.auth');

// Routes to simulate cloud calls to retrieve files
// Get User Profile Photo
Route::get('/users/{id}/photo', function(string|int $id) {
    $user = UserModel::find($id);
    if ($user && $user->photo && Storage::exists($user->photo)) {
      $response = response()->file(Storage::path($user->photo), ["Cache-Control" => "no-cache"]);
      return $response;
    }
    return response()->json(["error" => "No profile photo found"], HttpStatusCodes::NOT_FOUND->value);
});

// Get Movie Cover Photo
Route::get('/movies/{id}/cover', function(string|int $id) {
    $loggedUser = auth()->user()->mapToDomain();

    if(!$loggedUser->isEmailConfirmed()) {
        return response()->json(["error" => "Forbidden Resource"], HttpStatusCodes::FORBIDDEN->value);
    }

    $movie = MovieModel::find($id);

    if ($movie && $movie->cover && Storage::exists($movie->cover)) {
      $response = response()->file(Storage::path($movie->cover), ["Cache-Control" => "no-cache"]);
      return $response;
    }
    return response()->json(["error" => "No cover photo found"], HttpStatusCodes::NOT_FOUND->value);
})->middleware('jwt.auth');

// Get terms of use PDF
Route::get('/terms-of-use', function() {
    if(Storage::exists("termos-de-uso.pdf")) {
      $response = response()->file(Storage::path("termos-de-uso.pdf"), ["Cache-Control" => "no-cache"]);
      return $response;
    }
    return response()->json(["error" => "No terms of use file found"], HttpStatusCodes::NOT_FOUND->value);
});

Route::post('/terms-of-use', function(Request $request) {
    
    $file = $request->file('terms');
    $file->storeAs('', 'termos-de-uso.pdf');
    return "ok";
});