<?php

namespace App\Http\Controllers\Backend\User\UserAgency;

use App\Http\Controllers\Controller;
use App\Models\Agencies;
use App\Models\Roles;
use App\Models\SmsContent;
use App\Models\TransshipmentCenters;
use App\Models\User;
use App\Notifications\PasswordResetNotify;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserAgencyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['users'] = DB::table('view_users_all_info')
            ->where('agency_code', Auth::user()->agency_code)
            ->get();

        $data['agencies'] = Agencies::where('id', Auth::user()->agency_code)->first();

        GeneralLog('Acente Kullanıcılar sayfası görüntülendi.');
        return view('backend.users.agency.index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data['roles'] = Roles::whereIn('display_name', AgencyRoles())->get();
        $data['agencies'] = Agencies::where('id', Auth::user()->agency_code)->first();

        return view('backend.users.agency.create', compact('data'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name_surname' => 'required',
            'email' => 'required',
            'phone' => 'required',
            'role' => 'required',
        ]);

        $password = RandomPassword();

        $email = $request->email . '@cumhuriyetkargo.com.tr';
        $email_there_is = DB::table('users')->where('email', $email)->exists();

        if ($email_there_is) {
            $request->flash();
            return back()->with('error', 'Bu mail adresi kullanılıyor. Lütfen farklı bir tane deneyin!');
        }

        ### Role Control
        $role = Roles::where('id', $request->role)->first();
        $collection = collect(AgencyRoles());
        if ($role === null || !($collection->contains($role->display_name))) {
            $request->flash();
            return back()->with('error', 'Lütfen geçerli bir yetki seçin!');
        }


        $insert = User::create([
            'name_surname' => tr_strtoupper($request->name_surname),
            'email' => $email,
            'phone' => $request->phone,
            'password' => Hash::make($password),
            'role_id' => $request->role,
            'agency_code' => Auth::user()->agency_code,
            'user_type' => 'Acente'
        ]);

        if ($insert) {

            $smsContent = SmsContent::where('key', 'user_reg')->first();
            $sms = str_replace(['[name_surname]', '[email]', '[password]'], [tr_strtoupper($request->name_surname), $email, $password], $smsContent->content);
            SendSMS($sms, CharacterCleaner($request->phone),'Kullanıcı Kayıt', 'CUMHURIYETK');

            $properties = array(['Ad Soyad' => tr_strtoupper($request->name_surname), 'E-Posta' => $email, 'Yetki' => $role->display_name]);
            GeneralLog('Kullanıcı oluşturuldu.', $properties);

            return back()->with('success', 'Kullanıcı Eklendi!');
        }
        return back()->with('error', 'Bir Hata Oluştu, Lütfen Daha Sonra Tekrar Deneyin.');
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = User::where('id', $id)->first();
        if ($data === null || $data->agency_code != Auth::user()->agency_code)
            return redirect(route('AgencyUsers.index'))->with('error', 'Kullanıcı bulunamadı');

        $agency = Agencies::where('id', Auth::user()->agency_code)->first();
        $data['roles'] = Roles::whereIn('display_name', AgencyRoles())->get();

        return view('backend.users.agency.edit', compact(['data', 'agency']));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $data = User::where('id', $id)->first();
        if ($data === null || $data->agency_code != Auth::user()->agency_code)
            return back()->with('error', 'Kullanıcı bulunamadı');

        $email = $request->email . '@cumhuriyetkargo.com.tr';
        $email_there_is = DB::table('users')
            ->where('email', $email)
            ->where('email', '<>', $email)
            ->exists();

        if ($email_there_is) {
            $request->flash();
            return back()->with('error', 'Bu mail adresi kullanılıyor. Lütfen farklı bir tane deneyin!');
        }

        $request->validate([
            'name_surname' => 'required',
            'email' => 'required',
            'phone' => 'required',
            'role' => 'required',
            'agency' => 'nullable|numeric',
            'tc' => 'nullable|numeric'
        ]);

        ### Role Control
        $role = Roles::where('id', $request->role)->first();
        $collection = collect(AgencyRoles());
        if ($role === null || !($collection->contains($role->display_name))) {
            $request->flash();
            return back()->with('error', 'Lütfen geçerli bir yetki seçin!');
        }

        $update = User::find($id)
            ->update([
                'name_surname' => tr_strtoupper($request->name_surname),
                'email' => $email,
                'phone' => $request->phone,
                'role_id' => $request->role,
                'agency_code' => Auth::user()->agency_code,
                'user_type' => 'Acente'
            ]);

        if ($update) return back()->with('success', 'Kullanıcı Güncellendi!');

        return back()->with('error', 'Bir Hata Oluştu, Lütfen Daha Sonra Tekrar Deneyin.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        # Control
        $user = User::where('id', $id)->first();
        if ($user === null || ($user->agency_code != Auth::user()->agency_code) || ($id == Auth::id()))
            return 0;

        $destroy = User::find(intval($id))->delete();
        if ($destroy) {
            return 1;
        }
        return 0;
    }

    public function agencyPasswordReset($id)
    {
        try {
            $id = Decrypte4x($id);
        } catch (\Exception $e) {
            return back()->with('error', 'Hatalı Token');
        }

        # Control
        $user = User::where('id', $id)->first();
        if ($user === null || ($user->agency_code != Auth::user()->agency_code))
            return back()->with('error', 'Bulunamayan kullanıcı!');
        else {

            # control of max 3 times in the day
            $control = DB::table('activity_log')
                ->select('*')
                ->where('subject_id', $id)
                ->where('log_name', 'Password Reset')
                ->where('causer_id', Auth::id())
                ->whereRaw('TIMESTAMPDIFF(HOUR,created_at, NOW()) < 24')
                ->count();

            if ($control >= 3)
                return back()->with('error', '24 Saat içerisinde en fazla 3 kez şifre sıfırlayabilirsiniz!');


            $password = RandomPassword();
            $update = User::find($id)
                ->update([
                    'password' => Hash::make($password)
                ]);

            if ($update) {


                $smsContent = SmsContent::where('key', 'reset_password_info')->first();
                $sms = str_replace(['[name_surname]', '[password]'], [$user->name_surname, $password], $smsContent->content);
                SendSMS($sms, CharacterCleaner($user->phone), 'Şifre Sıfırlama', 'CUMHURIYETK');
                $role = Roles::where('id', Auth::user()->role_id)->first();
                $properties = ['Şifreyi Sıfırlayan' => Auth::user()->name_surname, 'Yetkisi' => $role->display_name, 'Şifresi Sıfırlanan Kullanıcı' => $user->name_surname];
                activity()
                    ->withProperties($properties)
                    ->inLog('Password Reset')
                    ->performedOn($user)
                    ->log('Kullanıcı şifresi sıfırlandı.');

                $role = Roles::where('id', Auth::user()->role_id)->first();
                ## => Notification
                User::find($id)
                    ->notify(new PasswordResetNotify('Şifreniz bir yetkili (' . Auth::user()->name_surname . ' - ' . $role->display_name . ' ) tarafından sıfırlandı.', '#'));

                return back()->with('success', 'Kullanıcının şifresi başarıyla sıfırlandı!');

            } else
                return back()->with('error', 'Bir hata oluştu, lütfen daha sonra tekrar deneyin!');
        }
    }

    public function userLogs()
    {
        $data['users'] = DB::table('view_users_all_info')
            ->where('agency_code', Auth::user()->agency_code)
            ->get();

        $data['agencies'] = Agencies::where('id', Auth::user()->agency_code)->first();

        GeneralLog('Acente kullanıcı hareketleri sayfası görüntülendi.');
        return view('backend.users.agency.logs', compact(['data']));
    }

    public function getUserLogs(Request $request)
    {
        $user_id = Decrypte4x($request->token);

        $code = User::where('id', $user_id)->first();
        $usersOfAgency = User::where('agency_code', $code->agency_code)->select('id')->get();

        $startDate = $request->has('start_date') ? $request->start_date . ' 00:00:00' : date('Y-m-d') . ' 00:00:00';
        $finish_date = $request->has('finish_date') ? $request->finish_date . ' 23:59:59' : date('Y-m-d') . ' 23:59:59';

        if (($request->user && $request->user != '')) {

            # Control
            $user = User::where('id', $request->user)->first();
            if ($user === null || ($user->agency_code != $code->agency_code))
                $request->user = -111;

            $logs = DB::table('view_user_log_detail')
                ->whereRaw("created_at between '" . $startDate . "'  and '" . $finish_date . "'")
                ->whereRaw($request->filled('agency') ? 'agency_code=' . $request->agency : '1 > 0')
                ->where("causer_id", $request->user)
                ->whereIn('log_name', UsersLogNames())
                ->limit(maxLogQuantity());
        } else {
            $logs = DB::table('view_user_log_detail')
                ->whereRaw("created_at between '" . $startDate . "'  and '" . $finish_date . "'")
                ->whereIn('causer_id', $usersOfAgency)
                ->whereIn('log_name', UsersLogNames())
                ->limit(maxLogQuantity());
        }
        return datatables()->of($logs)
            ->setRowId(function ($log) {
                return "logs-item-" . $log->id;
            })
            ->editColumn('agency', function ($logs) {
                return $logs->branch_city . '/' . $logs->branch_district . '-' . $logs->branch_name;
            })
            ->addColumn('properties', 'backend.users.gm.columns.properties')
            ->addColumn('properties_detail', function ($log) {
                return $log->properties;
            })
            ->rawColumns(['properties'])
            ->make(true);
    }
}
