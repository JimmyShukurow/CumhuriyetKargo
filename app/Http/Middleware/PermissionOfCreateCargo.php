<?php

namespace App\Http\Middleware;

use App\Models\Agencies;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PermissionOfCreateCargo
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
        $user = Auth::user();

        if ($user->user_type != 'Acente')
            return redirect(route(getUserFirstPage()))
                ->with('error', 'Kargo kesimininze izin yok!');

        $agency = Agencies::find($user->agency_code);

        if ($agency->permission_of_create_cargo == '0')
            return redirect(route(getUserFirstPage()))
                ->with('error', 'Kargo kesimininze izin yok!');
        else
            return $next($request);
    }
}
