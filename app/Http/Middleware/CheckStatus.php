<?php

namespace App\Http\Middleware;

use App\Models\Agencies;
use App\Models\TransshipmentCenters;
use Carbon\Carbon;
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
        if (Auth::user()->status == 1) {


            # Şube Kontrol Statü - Acente/Aktarma START
            if (Auth::user()->user_type == 'Acente') {
                $agency = Agencies::find(Auth::user()->agency_code);

                if ($agency == null) {
                    Auth::logout();
                    return redirect(route('Login'))
                        ->with('error', 'Kayıtlı olmayan şube!');
                }

                if ($agency->status == '1')
                    return $next($request);
                else {
                    $message = $agency->status_description != '' ? 'Şubeniz [' . $agency->status_description . '] gerekçesiyle pasif edilmiştir!' : 'Şubeniz pasif edilmiştir. Detaylı bilgi için sistem destek ekibine ulaşın.';

                    activity()
                        ->inLog('User Blocked!')
                        ->withProperties(['ip_address' => $request->ip(), 'message' => $message, 'time' => Carbon::now()])
                        ->log('Girişe izin verilmedi')
                        ->causer(Auth::id());

                    Auth::logout();
                    return redirect(route('Login'))
                        ->with('error', $message);
                }

            } else if (Auth::user()->user_type == 'Aktarma') {

                $tc = TransshipmentCenters::find(Auth::user()->tc_code);

                if ($tc == null) {
                    Auth::logout();
                    return redirect(route('Login'))
                        ->with('error', 'Kayıtlı olmayan şube!');
                }

                ## mehmet ateş

                if ($tc->status == '1')
                    return $next($request);
                else {
                    $message = $tc->status_description != '' ? 'Şubeniz [' . $tc->status_description . '] gerekçesiyle pasif edilmiştir!' : 'Şubeniz pasif edilmiştir. Detaylı bilgi için sistem destek ekibine ulaşın.';

                    activity()
                        ->inLog('User Blocked!')
                        ->withProperties(['ip_address' => $request->ip(), 'message' => $message, 'time' => Carbon::now()])
                        ->log('Girişe izin verilmedi')
                        ->causer(Auth::id());

                    Auth::logout();
                    return redirect(route('Login'))
                        ->with('error', $message);
                }
            }
            # Şube Kontrol Statü - Acente/Aktarma END

        } else {
            $message = Auth::user()->status_description != '' ? 'Hesabınız [' . Auth::user()->status_description . '] gerekçesiyle pasif edilmiştir!' : 'Hesabınız pasif edilmiştir. Detaylı bilgi için sistem destek ekibine ulaşın.';
            Auth::logout();

            return redirect(route('Login'))
                ->with('error', $message);
        }
    }
}
