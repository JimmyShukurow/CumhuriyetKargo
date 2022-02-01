<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Agencies;
use App\Models\Motivation_Lyrics;
use App\Models\SecurityCodes;
use App\Models\SmsContent;
use App\Models\User;
use App\Notifications\PasswordResetNotify;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Routing\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class DefaultController extends Controller
{

    public function index()
    {
        return view('backend.default.index');
    }

    public function login()
    {
        $FalseQuantity = Motivation_Lyrics::all()->where('used', '0')->count();

        if ($FalseQuantity < 5) {
            DB::table('motivation_lyrics')->update(['used' => '0']);
        }

        $lyrics = Motivation_Lyrics::all()->where('used', '0')->random(3);

        return view('backend.default.login', compact('lyrics'));
    }

    public function authenticate(Request $request)
    {
        $request->flash();
        $credentials = $request->only('email', 'password');
        $remember_me = $request->has('remember_me') ? true : false;

        if (Auth::attempt($credentials, $remember_me)) {

            $user = Auth::user();

            if ($user->user_type == 'Acente X') {

                $password = Crypte4x($request->password);

                $agency = Agencies::find($user->agency_code);
                if ($agency->ip_address != $request->ip()) {

                    Auth::logout();

                    $user = User::find($user->id);

                    $code_exist_control = DB::select("SELECT *, TIMESTAMPDIFF(SECOND,created_at,NOW()) as second_diff
                    FROM security_codes WHERE user_id=" . $user->id . " and security_codes.status = '0' and TIMESTAMPDIFF(SECOND,created_at,NOW()) < 300 and reason='Login Code'");

                    if ($code_exist_control == null) {

                        # too many try control < 5
                        $TryQuantity = DB::select("SELECT count(*) as quantity FROM security_codes
                        where TIMESTAMPDIFF(HOUR,created_at,NOW()) < 24 and reason = 'Login Code' and user_id =" . $user->id);

                        if ($TryQuantity[0]->quantity >= 20)
                            return redirect(route('Login'))
                                ->with('error', 'Yabancı IP Adresi üzerinden çok fazla deneme yaptınız. Lütfen sisteme kayıtlı IP adresiniz üzerinden giriş yapın veya bir süre sonra tekrar deneyin! Bunun bir hata olduğunu düşünüyorsanız lütfen sistem destek ekibine ulaşın.');

                        $code = CreateSecurityCode();
                        $smsContent = SmsContent::where('key', 'login_security_code')->first();
                        $sms = str_replace(['[name_surname]', '[code]'], [$user->name_surname, $code], $smsContent->content);

                        SendSMS($sms, CharacterCleaner($user->phone), 'Şifre Sıfırlama', 'CUMHURIYETK', $ctn = '');

                        $insert = SecurityCodes::create([
                            'user_id' => $user->id,
                            'code' => $code,
                            'reason' => 'Login Code'
                        ]);

                        $code_exist_control = DB::select("SELECT *, TIMESTAMPDIFF(SECOND,created_at,NOW()) as second_diff
                        FROM security_codes WHERE user_id=" . $user->id . " and security_codes.status = '0' and TIMESTAMPDIFF(SECOND,created_at,NOW()) < 300 and reason='Login Code'");


                        $diffSeconds = 300 - $code_exist_control[0]->second_diff;
                        $dateHumens['minute'] = '0' . intval($diffSeconds / 60);
                        $dateHumens['seconds'] = $diffSeconds % 60;
                        $dateHumens['seconds'] = $dateHumens['seconds'] < 10 ? '0' . $dateHumens['seconds'] : $dateHumens['seconds'];

                        $userAllInfo = DB::table('view_users_all_info')
                            ->where('id', $user->id)
                            ->first();

                        return view('backend.default.login-code', compact(['diffSeconds', 'user', 'userAllInfo', 'dateHumens', 'password']));

                    } else {

                        $diffSeconds = 300 - $code_exist_control[0]->second_diff;
                        $dateHumens['minute'] = '0' . intval($diffSeconds / 60);
                        $dateHumens['seconds'] = $diffSeconds % 60;
                        $dateHumens['seconds'] = $dateHumens['seconds'] < 10 ? '0' . $dateHumens['seconds'] : $dateHumens['seconds'];

                        $userAllInfo = DB::table('view_users_all_info')
                            ->where('id', $user->id)
                            ->first();

                        return view('backend.default.login-code', compact(['diffSeconds', 'user', 'userAllInfo', 'dateHumens', 'password']));
                    }

                }

            }

            $user = User::find(Auth::id());
            $properties = ['Login IP' => request()->ip()];

            activity()
                ->withProperties($properties)
                ->performedOn($user)
                ->inLog('Login')
                ->log('Sisteme giriş yapıldı!');

            Auth::logoutOtherDevices(\request('password'));


            return redirect()->intended(route(getUserFirstPage()))->with('success', 'Hoşgeldin ' . Auth::user()->name_surname);

        } else {
            return back()->with('error', 'E-Mail veya Şifre Hatalı!');
        }
    }

    public function logout()
    {
        GeneralLog('Sistemden çıkış yapıldı.');
        Auth::logout();
        return redirect(route('Login'))->with('success', 'Güvenli Çıkış Yaptınız.');
    }

    public function closeTheVirtualLogin($id)
    {
        if (Session::exists('virtual-login')) {
            Auth::loginUsingId(Decrypte4x($id));
            $route = getUserFirstPage();
            Session::remove('virtual-login');
            return redirect()->intended(route($route))->with('success', 'Sanal giriş sonlandırıldı.');
        } else {
            return redirect(route('Login'));
        }
    }

    public function forgetPassword($TimeOut = false)
    {
        return view('backend.default.forget-password', compact(['TimeOut']));
    }

    public function confirmEmail(Request $request)
    {
        $user = User::where('email', $request->email)
            ->where('phone', $request->phone)
            ->first();

        $request->flash();
        if ($user === null)
            return back()
                ->with('error', 'Sisteme kayıtlı böyle bir mail adresi ve telefon numarası bulunamadı!');
        else {

            # too many try control < 5
            $TryQuantity = DB::select('SELECT count(*) as quantity FROM security_codes
            where TIMESTAMPDIFF(HOUR,created_at,NOW()) < 24 and user_id =' . $user->id);

            if ($TryQuantity[0]->quantity >= 5)
                return redirect(route('Login'))
                    ->with('error', 'Çok fazla deneme yaptınız. Lütfen bir süre sonra tekrar deneyin! Bunun bir hata olduğunu düşünüyorsanız lütfen sistem destek ekibine ulaşın.');


            # too many recover control < 3
            $RecoverQuantity = DB::select("SELECT count(*) as quantity FROM security_codes
            where TIMESTAMPDIFF(HOUR,created_at,NOW()) < 24 and security_codes.status = '1' and user_id =" . $user->id . " and reason='Recover Password'");

            if ($RecoverQuantity[0]->quantity >= 3)
                return redirect(route('Login'))
                    ->with('error', '24 saat içerisinde en fazla 3 kez şifre sıfırlayabilirsiniz!');

            #code exist control
            $code_exist_control = DB::select("SELECT *, TIMESTAMPDIFF(SECOND,created_at,NOW()) as second_diff
            FROM security_codes
            WHERE user_id =" . $user->id . " and security_codes.status = '0' and TIMESTAMPDIFF(SECOND,created_at,NOW()) < 300");

            if ($code_exist_control == null) {
                //  echo 'no - code';


                $code = CreateSecurityCode();
                $smsContent = SmsContent::where('key', 'reset_password')->first();
                $sms = str_replace(['[name_surname]', '[code]'], [$user->name_surname, $code], $smsContent->content);

                SendSMS($sms, CharacterCleaner($request->phone), 'Şifre Sıfırlama', 'CUMHURIYETK', $ctn = '');

                $insert = SecurityCodes::create([
                    'user_id' => $user->id,
                    'code' => $code
                ]);

                if ($insert) {
                    #SecondDiffrent 5 * 60 = 300 seconds
                    $timeOfCode = DB::table('security_codes')
                        ->where('code', $code)->where('status', '0')
                        ->orderByDesc('created_at')->first();
                    $date = Carbon::parse($timeOfCode->created_at);
                    $now = Carbon::now();
                    $diffSeconds = 300 - $date->diffInSeconds($now);

                    $dateHumens['minute'] = '0' . intval($diffSeconds / 60);
                    $dateHumens['seconds'] = $diffSeconds % 60;
                    $dateHumens['seconds'] = $dateHumens['seconds'] < 10 ? '0' . $dateHumens['seconds'] : $dateHumens['seconds'];

                    return redirect(route('recoverPassword', Crypte4x($user->id)));

                } else
                    return back()
                        ->with('error', 'Bir hata oluştu, lütfen daha sonra tekrar deneyin!');

            } else
                return redirect(route('recoverPassword', Crypte4x($user->id)));

        }

    }

    public function confirmLoginSecurityCode(Request $request)
    {

        $user_id = Decrypte4x($request->token);
        $code = $request->code;

        $code = SecurityCodes::where('code', $code)
            ->where('user_id', $user_id)
            ->where('status', '0')
            ->orderBy('created_at', 'desc')
            ->first();

        if ($code === null)
            return response()
                ->json([
                    'status' => 0,
                    'message' => 'Güvenlik kodu hatalı!'
                ]);

        $date = Carbon::parse($code->created_at);
        $now = Carbon::now();
        $diffSeconds = 300 - $date->diffInSeconds($now);

        if ($diffSeconds <= 0)
            return response()
                ->json([
                    'status' => 0,
                    'message' => 'Bu kod zaman aşımına uğramış, lütfen cep numaranıza son gönderilen güvenlik kodunu giriniz!'
                ]);


        #Confirmed
        $update = SecurityCodes::find($code->id)
            ->where('user_id', $user_id)
            ->where('status', '0')
            ->update([
                'status' => '1'
            ]);

        Auth::loginUsingId($user_id);
        Auth::logoutOtherDevices(Decrypte4x($request->niko_token));

        $user = User::find(Auth::id());
        $properties = ['Login IP' => request()->ip()];
        activity()
            ->withProperties($properties)
            ->performedOn($user)
            ->inLog('Login')
            ->log('Sisteme güvenlik kodu kullanılarak giriş yapıldı!');


        return response()
            ->json([
                'status' => 1,
                'message' => 'Güvenlik kodu doğrulandı. Hoşgeldiniz, Sn. ' . Auth::user()->name_surname
            ]);
    }


    public function confirmSecurityCode(Request $request)
    {

        $user_id = Decrypte4x($request->token);
        $code = $request->code;

        $code = SecurityCodes::where('code', $code)
            ->where('user_id', $user_id)
            ->where('status', '0')
            ->orderBy('created_at', 'desc')
            ->first();

        if ($code === null)
            return response()
                ->json([
                    'status' => 0,
                    'message' => 'Güvenlik kodu hatalı!'
                ]);

        $date = Carbon::parse($code->created_at);
        $now = Carbon::now();
        $diffSeconds = 300 - $date->diffInSeconds($now);

        if ($diffSeconds <= 0)
            return response()
                ->json([
                    'status' => 0,
                    'message' => 'Bu kod zaman aşımına uğramış, lütfen cep numaranıza son gönderilen güvenlik kodunu giriniz!'
                ]);


        #Confirmed
        $update = SecurityCodes::find($code->id)
            ->where('user_id', $user_id)
            ->where('status', '0')
            ->update([
                'status' => '1'
            ]);

        $new_password = RandomPassword();
        $update = User::find($user_id)
            ->update([
                'password' => Hash::make($new_password)
            ]);

        $user = User::where('id', $user_id)->first();

        $smsContent = SmsContent::where('key', 'reset_password_info2')->first();
        $sms = str_replace(['[name_surname]', '[password]'], [tr_strtoupper($user->name_surname), $new_password], $smsContent->content);
        SendSMS($sms, CharacterCleaner($user->phone), 'Şifre Sıfırlama', 'CUMHURIYETK');

        ## => Notification
        User::find($user->id)
            ->notify(new PasswordResetNotify('Şifrenizi sıfırladınız. Bunu siz yapmadıysanız lütfen sistem destek ekibine ulaşın!', '#'));

        activity()
            ->causedBy($user)
            ->inLog('Password Reset')
            ->log('Şifrenizi sıfırladınız.');

        return response()
            ->json([
                'status' => 1,
                'message' => 'Güvenlik kodu doğrulandı . Yeni şifreniz cep numaranıza sms olarak gönderilmiştir!'
            ]);
    }

    public function recoverPassword($UserID)
    {
        $user = User::where('id', Decrypte4x($UserID))->first();

        #code exist control
        $code_exist_control = DB::select("SELECT *, TIMESTAMPDIFF(SECOND,created_at,NOW()) as second_diff
        FROM security_codes
        WHERE user_id=" . $user->id . " and security_codes.status = '0' and TIMESTAMPDIFF(SECOND,created_at,NOW()) < 300");

        if ($code_exist_control == null) {

            return redirect(route('forgetPassword'))
                ->with('time_out', 'Zaman aşımına uğradınız!');

        } else {
            $diffSeconds = 300 - $code_exist_control[0]->second_diff;
            $dateHumens['minute'] = '0' . intval($diffSeconds / 60);
            $dateHumens['seconds'] = $diffSeconds % 60;
            $dateHumens['seconds'] = $dateHumens['seconds'] < 10 ? '0' . $dateHumens['seconds'] : $dateHumens['seconds'];

            $userAllInfo = DB::table('view_users_all_info')
                ->where('id', $user->id)
                ->first();

            return view('backend.default.confirm-code', compact(['diffSeconds', 'user', 'userAllInfo', 'dateHumens']));
        }

    }
}
