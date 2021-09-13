<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApiAuthenticate
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */

    protected function redirectTo($request)
    {
        if (! $request->expectsJson()) {
            return route('losgin');
        }
    }

    public function handle(Request $request, Closure $next)
    {
        if (Auth::guest())
            return response()
                ->json('no-authorization');
        else
            return $next($request);
    }
}
