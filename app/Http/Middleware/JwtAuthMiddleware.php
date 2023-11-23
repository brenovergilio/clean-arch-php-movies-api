<?php

namespace App\Http\Middleware;

use App\Infra\Handlers\JWTHandler;
use App\Models\UserModel;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Throwable;

class JwtAuthMiddleware
{
    public function __construct(private JWTHandler $jwtHandler) {}

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        try {
            $jwtHandler = new JWTHandler();
            $token = explode(" ", $request->header('Authorization'))[1];

            $decodedUser = $jwtHandler->decode($token);
            $user = UserModel::find($decodedUser->id);

            Auth::login($user);

            return $next($request);
        } catch (Throwable $th) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
    }
}
