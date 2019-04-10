<?php

namespace App\Http\Middleware;

use Closure;
use Tymon\JWTAuth\Facades\JWTAuth;

class ApiRoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $role)
    {
        $roles = is_array($role)
            ? $role
            : explode('|', $role);

        if (JWTAuth::parseToken()->authenticate()->hasAnyRole($roles))
            return $next($request);

        return response()->json([
            'success' => false,
            'message' => 'User does not have the right roles.'
        ]);
    }
}
