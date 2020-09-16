<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
use Illuminate\Http\Request;

class ApiAuthenticate
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
        if(!$this->guard()->user()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $next($request);
    }

    protected function guard()
    {
        return Auth::guard();
    }
}