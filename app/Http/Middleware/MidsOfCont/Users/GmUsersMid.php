<?php

namespace App\Http\Middleware\MidsOfCont\Users;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class GmUsersMid
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
            'user.gm.Index',
            'user.gm.AddUser',
            'user.gm.Insert',
            'user.gm.EditUser',
            'user.gm.Update',
            'user.gm.destroyUser',
            'user.gm.Logs',
            'user.gm.GetLogs',
            'user.gm.checkEmail'
        ];

        $result = DB::table('role_permissions')
            ->where('role_id', Auth::user()->role_id)
            ->whereIn('link', $moduleLink)
            ->count();

        if ($result > 0) {
            return $next($request);
        } else {

            activity()
                ->inLog('Dangerous Log')
                ->withProperties(['middleware' => 'GmUsersMid', 'sub_modules' => $moduleLink])
                ->log('Yetkisiz giriş reddedildi.');


            $route = getUserFirstPage();
            return redirect()->route($route)->with('error', 'Yetkiniz yok!');
        }
    }
}
