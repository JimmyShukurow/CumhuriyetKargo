<?php

namespace App\Http\Middleware\MidsOfCont;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SenderCurrentsMid
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
            'senderCurrents.ajaxTransaction',
            'senderCurrents.getCurrents',

            'SenderCurrents.index',
            'SenderCurrents.edit',
            'SenderCurrents.update',
            'SenderCurrents.store',
            'SenderCurrents.destroy',
            'SenderCurrents.create',
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
                ->withProperties(['middleware' => 'SenderCurrentsMid', 'sub_modules' => $moduleLink])
                ->log('Yetkisiz giriş reddedildi.');

            $route = getUserFirstPage();
            return redirect()->route($route)->with('error', 'Yetkiniz yok!');
        }


    }
}
