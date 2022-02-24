<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class Owner
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check() && Auth::user()['role'] != 'owner') {
            return response()->json([
                'status' => 'Forbidden',
                'statusCode' => 403,
                'message' => 'You not allowed',
            ], 403);
        }
        $acceptHeader = $request->header('Accept');
        if ($acceptHeader != 'application/json') {
            return response()->json([
                'status' => 'Forbidden',
                'statusCode' => 406,
                'message' => 'Must using header "Accept: application/json"',
            ], 406);
        }
        return $next($request);
        
    }
}
