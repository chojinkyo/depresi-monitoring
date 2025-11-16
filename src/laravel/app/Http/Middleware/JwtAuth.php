<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Facades\JWTAuth as FacadesJWTAuth;

class JwtAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$guards): Response
    {
        try
        {
            $auth=FacadesJWTAuth::parseToken()->authenticate();
            return $next($request);
        }
        catch(\Exception $e)
        {
            return new JsonResponse(["message"=>"Unauthorized", 'err'=>$e->getMessage()], 401);

        }
        
    }
}
