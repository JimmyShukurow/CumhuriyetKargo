<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckStatus
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
        if (Auth::user()->status == 1)
            return $next($request);
        else {
            $message = Auth::user()->status_description != '' ? Auth::user()->status_description : 'Hesabınız pasif edilmiştir. Detaylı bilgi için sistem destek ekibine ulaşın.';
            Auth::logout();

            return redirect(route('Login'))
                ->with('error', $message);
        }
    }
}
