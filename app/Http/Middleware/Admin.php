<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Admin
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {

        if (!Auth::guest() && Auth::user()->role_id == 1) {
            return $next($request);
        } else {
            return back()->with('error', 'Yetkiniz yok!');
            return redirect(route('Login'))->with('error', 'Lütfen Giriş Yapın!');
        }

    }
}
