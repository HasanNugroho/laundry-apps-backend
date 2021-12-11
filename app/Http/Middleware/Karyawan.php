<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class Karyawan
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
        if (Auth::check() && Auth::user()->role == 'karyawan' && Auth::user()['email_verified_at'] != null) {
            $acceptHeader = $request->header('Accept');
            if ($acceptHeader != 'application/json') {
                return response()->json([
                    'status' => 'Forbidden',
                    'statusCode' => 406,
                    'message' => 'Must using JSON',
                ], 406);
            }
            return $next($request);
          }

          return response()->json([
			'status' => 'Forbidden',
			'statusCode' => 403,
            'message' => 'You not allowed',
		], 403);
    }
}
