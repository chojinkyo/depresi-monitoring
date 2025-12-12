<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleCheck
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        $user=Auth::guard('web')->user() ?? $request->user();
        if($user==null)
            abort(401, 'Unauthenticated');
        if(in_array($user->role, $roles))
            return $next($request);

        if($request->expectsJson())
            return response()->json(['error'=>'403 Forbidden'], 403);
        return redirect('/')->with('status', 'Unauthorized access');
    }
}
