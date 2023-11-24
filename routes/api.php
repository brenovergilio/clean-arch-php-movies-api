<?php

use App\Infra\Factories\UseCases\Auth\LoginUseCaseFactory;
use App\Presentation\Http\Controllers\Auth\LoginController;
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

Route::post('/login', function (Request $request) {
    $loginUseCase = LoginUseCaseFactory::make();
    $controller = new LoginController($loginUseCase);

    $httpRequest = new HttpRequest($request->all());
    $result = $controller->login($httpRequest);

    return response()->json($result->body, $result->statusCode->value);
});
