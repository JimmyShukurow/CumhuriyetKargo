<?php

namespace App\Http\Middleware\MidsOfCont;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TransferCarsMid
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
        $moduleLink = [
            'transfer.car.all',
            'getTransferCars',
            'TransferCars.index',
            'TransferCars.edit',
            'TransferCars.update',
            'TransferCars.store',
            'TransferCars.destroy',
            'TransferCars.create',
        ];

        $result = DB::table('role_permissions')
            ->where('role_id', Auth::user()->role_id)
            ->whereIn('link', $moduleLink)
            ->count();

        if ($result > 0)
            return $next($request);
        else {

            activity()
                ->inLog('Dangerous Log')
                ->withProperties(['middleware' => 'TransferCarsMid', 'sub_modules' => $moduleLink])
                ->log('Yetkisiz giriş reddedildi.');

            $route = getUserFirstPage();
            return redirect()->route($route)->with('error', 'Yetkiniz yok!');
        }
    }
}
