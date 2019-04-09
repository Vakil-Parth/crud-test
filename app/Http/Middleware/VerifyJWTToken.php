<?php

namespace App\Http\Middleware;

use Closure;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;

class VerifyJWTToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        try {
            JWTAuth::setToken($request->input('token'));
            $user = JWTAuth::toUser($request->input('token'));
        } catch (JWTException $e) {
            if($e instanceof TokenExpiredException) {

                return response()->json([
                    'success' => false,
                    'message' => 'token_expired'
                ]);
            } else if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException) {

                return response()->json([
                    'success' => false,
                    'message' => 'invalid token'
                ]);
            } else {

                return response()->json([
                    'success' => false,
                    'error'=>'Token is required'
                ]);
            }
        }

        return $next($request);
    }
}
