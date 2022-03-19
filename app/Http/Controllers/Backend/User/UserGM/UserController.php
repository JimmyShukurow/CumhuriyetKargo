<?php

namespace App\Http\Controllers\Backend\User\UserGM;

use App\Http\Controllers\Controller;
use App\Models\Agencies;
use App\Models\Roles;
use App\Models\SmsContent;
use App\Models\TransshipmentCenters;
use App\Models\User;
use App\Notifications\GeneralNotify;
use App\Notifications\PasswordResetNotify;

//use Dotenv\Validator;
use http\Env\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Testing\Fluent\Concerns\Has;
use Spatie\Activitylog\Models\Activity;
use Symfony\Component\Console\Input\Input;
use Symfony\Component\HttpKernel\CacheClearer\ChainCacheClearer;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{

    public function index()
    {
        $data['users'] = DB::table('view_users_general_info')->get();
        $data['agencies'] = Agencies::orderBy('agency_name')->get();
        $data['tc'] = TransshipmentCenters::all();
        $data['roles'] = Roles::all();

        GeneralLog('GM Kullanıcılar sayfası görüntülendi.');

        return view('backend.users.gm.index', compact('data'));
    }

    public function getAllUsers(Request $request)
    {
        $status = $request->status == 'Aktif' ? '1' : '0';

        $users = DB::table('view_users_all_info')
            ->whereRaw($request->agency ? 'agency_code=' . $request->agency : '1 > 0')
            ->whereRaw($request->tc ? 'tc_code=' . $request->tc : '1 > 0')
            ->whereRaw($request->role ? 'role_id=' . $request->role : '1 > 0')
            ->whereRaw($request->status ? "view_users_all_info.`status`='" . $status . "'" : '1 > 0')
            ->whereRaw($request->user_type ? "user_type='" . $request->user_type . "'" : '1 > 0')
            ->whereRaw($request->name_surname ? "name_surname like '%" . $request->name_surname . "%'" : '1 > 0');

        return datatables()->of($users)
            ->setRowId(function ($user) {
                return "user-item-" . $user->id;
            })
            ->editColumn('status', function ($user) {
                return $user->status == '1' ? '<b class="text-success">Aktif</b>' : '<b class="text-danger">Pasif</b>';
            })
            ->addColumn('edit', 'backend.users.gm.columns.edit')
            ->rawColumns(['edit', 'status'])
            ->make(true);
    }

    public function addUser()
    {
        $data['roles'] = Roles::all();
        $data['agencies'] = Agencies::all();
        $data['transshipment_centers'] = TransshipmentCenters::all();

        activity()
            ->inLog("General Motion")
            ->log("Gm Kullanıcı ekle sayfası görüntülendi.");

        return view('backend.users.gm.create', compact('data'));
    }

    public function insertUser(Request $request)
    {
        $request->validate([
            'name_surname' => 'required',
            'email' => 'required',
            'phone' => 'required',
            'role' => 'required',
            'agency' => 'nullable|numeric',
            'tc' => 'nullable|numeric'
        ]);

        $password = RandomPassword();

        $tc = $request->tc != '' ? $request->tc : null;
        $agency = $request->agency != '' ? $request->agency : null;
        $type = $tc != null ? 'Aktarma' : 'Acente';

        if ($tc == null and $agency == null)
            return back()->with('error', 'Kullanıcı Tipini Acente veya Akatarma Olarak Belirleyin.');

        $email = $request->email . '@cumhuriyetkargo.com.tr';
        $email_there_is = DB::table('users')->where('email', $email)->exists();

        if ($email_there_is) {
            $request->flash();
            return back()->with('error', 'Bu mail adresi kullanılıyor. Lütfen farklı bir tane deneyin!');
        }

        $insert = User::create([
            'name_surname' => tr_strtoupper($request->name_surname),
            'email' => $email,
            'phone' => $request->phone,
            'password' => Hash::make($password),
            'role_id' => $request->role,
            'agency_code' => $agency,
            'tc_code' => $tc,
            'user_type' => $type,
            'creator_user' => Auth::id()
        ]);

        if ($insert) {
            $smsContent = SmsContent::where('key', 'user_reg')->first();
            $sms = str_replace(['[name_surname]', '[email]', '[password]'], [tr_strtoupper($request->name_surname), $email, $password], $smsContent->content);
            SendSMS($sms, CharacterCleaner($request->phone), 'Kullanıcı Kayıt', 'CUMHURIYETK');
            return back()->with('success', 'Kullanıcı Eklendi!');
        }
        return back()->with('error', 'Bir Hata Oluştu, Lütfen Daha Sonra Tekrar Deneyin.');
    }

    public function checkEmail(Request $request)
    {

        $email = $request->email . '@cumhuriyetkargo.com.tr';
        if (trim($email) == "@cumhuriyetkargo.com.tr")
            return 1;

        $there_is = DB::table('users')->where('email', $email)->exists();

        if ($there_is) return 1;
        else  return 0;

        return $request->email;
    }

    public function userInfo(Request $request)
    {
        $data['user'] = DB::table('view_users_all_info')
            ->where('id', $request->user)
            ->first();

        $data['user_log'] = DB::table('activity_log')
            ->where('causer_id', $request->user)
            ->limit(30)
            ->orderBy('created_at', 'desc')
            ->get();

        $user = DB::table('users')
            ->where('id', $data['user']->id)
            ->first();

        $data['creator'] = DB::table('view_users_all_info')
            ->where('id', $user->creator_user)
            ->first();

        return response()
            ->json($data, 200);

    }

    public function editUser($id)
    {
        $exists = DB::table('users')->where('id', $id)->first();

        if ($exists === null) return redirect(route('user.gm.Index'))->with('error', 'Kullanıcı Bulunamadı!');

        $user = User::where('id', $id)->first();
        $data['roles'] = Roles::all();
        $data['agencies'] = Agencies::all();
        $data['transshipment_centers'] = TransshipmentCenters::all();

        activity()
            ->performedOn($user)
            ->inLog("General Motion")
            ->log('GM Kullanıcılar düzenleme sayfası görüntülendi.');

        return view('backend.users.gm.edit', compact(['data', 'user']));
    }

    public function updateUser(Request $request, $id)
    {

        $email = $request->email . '@cumhuriyetkargo.com.tr';
        $email_there_is = DB::table('users')
            ->where('email', $email)
            ->where('email', '<>', $email)
            ->exists();

        if ($email_there_is) {
            $request->flash();
            return back()->with('error', 'Bu mail adresi kullanılıyor. Lütfen farklı bir tane deneyin!');
        }

        if ($request->password != '') {

            $request->validate([
                'name_surname' => 'required',
                'email' => 'required',
                'phone' => 'required',
                'password' => 'min:8',
                'role' => 'required',
                'agency' => 'nullable|numeric',
                'tc' => 'nullable|numeric'
            ]);

            $tc = $request->tc != '' ? $request->tc : null;
            $agency = $request->agency != '' ? $request->agency : null;
            $type = $tc != null ? 'Aktarma' : 'Acente';

            $insert = User::find($id)
                ->update([
                    'name_surname' => tr_strtoupper($request->name_surname),
                    'email' => $email,
                    'phone' => $request->phone,
                    'password' => Hash::make($request->password),
                    'role_id' => $request->role,
                    'agency_code' => $agency,
                    'tc_code' => $tc,
                    'user_type' => $type
                ]);

        } else {

            $request->validate([
                'name_surname' => 'required',
                'email' => 'required',
                'phone' => 'required',
                'role' => 'required',
                'agency' => 'nullable|numeric',
                'tc' => 'nullable|numeric'
            ]);

            $tc = $request->tc != '' ? $request->tc : null;
            $agency = $request->agency != '' ? $request->agency : null;
            $type = $tc != null ? 'Aktarma' : 'Acente';

            $insert = User::find($id)
                ->update([
                    'name_surname' => tr_strtoupper($request->name_surname),
                    'email' => $email,
                    'phone' => $request->phone,
                    'role_id' => $request->role,
                    'agency_code' => $agency,
                    'tc_code' => $tc,
                    'user_type' => $type
                ]);

        }


        if ($insert) return back()->with('success', 'Kullanıcı Güncellendi!');

        return back()->with('error', 'Bir Hata Oluştu, Lütfen Daha Sonra Tekrar Deneyin.');
    }

    public function destroyUser(Request $request)
    {
        $update = User::find($request->destroy_id)
            ->update(['deleting_user' => Auth::id()]);

        if ($update) {
            $destroy = User::find(intval($request->destroy_id))->delete();
            if ($destroy)
                return 1;
            else
                return 0;
        }
    }

    public function userLogs()
    {
        $data['agencies'] = Agencies::all();
        $data['tc'] = TransshipmentCenters::all();
        $data['log_names'] = DB::table('activity_log')
            ->select('log_name')
            ->groupBy('log_name')
            ->get();

        GeneralLog('GM Kullanıcı Log Sayfası Görüntülendi');
        return view('backend.users.gm.logs', compact(['data']));
    }

    public function getUserLogs(Request $request)
    {
        $startDate = $request->has('start_date') ? $request->start_date . ' 00:00:00' : date('Y-m-d') . ' 00:00:00';
        $finish_date = $request->has('finish_date') ? $request->finish_date . ' 23:59:59' : date('Y-m-d') . ' 23:59:59';

        $name_surname = $request->name_surname;
        $agency = $request->agency;
        $tc = $request->tc;
        $log_name = $request->log_name;

        if (($name_surname != '') || $agency != '' || $tc != '' || $log_name != '') {
            $logs = DB::table('view_user_log_detail')
                ->whereRaw("created_at between '" . $startDate . "'  and '" . $finish_date . "'")
                ->whereRaw($request->filled('agency') ? 'agency_code=' . $agency : '1 > 0')
                ->whereRaw($request->filled('tc') ? 'tc_code=' . $tc : '1 > 0')
                ->whereRaw($request->filled('log_name') ? "log_name='" . $log_name . "'" : '1 > 0')
                ->whereRaw("name_surname like '%" . $name_surname . "%'");
        } else {
            $logs = DB::table('view_user_log_detail')
                ->whereRaw("created_at between '" . $startDate . "'  and '" . $finish_date . "'")
                ->get();
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

    public function userPasswordReset(Request $request)
    {
        $id = intval($request->user);

        # Control
        $user = User::where('id', $id)->first();
        if ($user === null)
            return response()->json([
                'status' => '0',
                'description' => 'Bulunamayan kullanıcı!'
            ], 200);
        else {

            # control of max 5 times in the day
            $control = DB::table('activity_log')
                ->select('*')
                ->where('subject_id', $id)
                ->where('log_name', 'Password Reset')
                ->where('causer_id', Auth::id())
                ->whereRaw('TIMESTAMPDIFF(HOUR,created_at, NOW()) < 24')
                ->count();

            if ($control >= 5)
                return response()->json([
                    'status' => '0',
                    'description' => '24 Saat içerisinde en fazla 5 kez şifre sıfırlayabilirsiniz!'
                ], 200);


            $password = RandomPassword();
            $update = User::find($id)
                ->update([
                    'password' => Hash::make($password)
                ]);

            if ($update) {
                $smsContent = SmsContent::where('key', 'reset_password_info')->first();
                $sms = str_replace(['[name_surname]', '[email]', '[password]'], [tr_strtoupper($user->name_surname), $user->email, $password], $smsContent->content);
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

                return response()
                    ->json(['status' => 1], 200);

            } else
                return response()->json([
                    'status' => '0',
                    'description' => 'Bir hata oluştu, lütfen daha sonra tekrar deneyin!'
                ], 200);
        }
    }


    public function changeStatus(Request $request)
    {
        $rules = [
            'status' => 'required|in:0,1',
            'user' => 'required|numeric'
        ];
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'status' => '-1',
                'errors' => $validator->getMessageBag()->toArray()
            ], 200);
        }

        $update = User::find(intval($request->user))
            ->update([
                'status' => $request->status,
                'status_description' => $request->status == '1' ? '' : $request->status_description
            ]);

        if ($update) {

            $statu = $request->status == '1' ? 'aktif' : 'pasif';
            $user = User::where('id', intval($request->user))->first();
            $properties = [
                'Eylemi gerçekleştiren' => Auth::user()->name_surname,
                'id\'si' => Auth::id(),
                'İşlem Yapılan Kullanıcı' => $user->name_surname,
                'Statü' => $statu,
                'Statü Açıklama' => $request->status == '1' ? '' : $request->status_description
            ];

            $log = $user->name_surname . " İsimli kullanıcı " . $statu . ' hale getirildi';
            activity()
                ->performedOn($user)
                ->inLog('User Enabled-Disabled')
                ->withProperties($properties)
                ->log($log);

            User::find($request->user)
                ->notify(new GeneralNotify('Hesabınız ' . $statu . ' hale getirildi.', '#'));

            return response()->json(['status' => 1], 200);
        } else
            return response()->json([
                'status' => 0,
                'message' => "Bir hata oluştu, lütfen daha sonra tekrar deneyin!"
            ], 200);

    }

    public function virtualLogin($UserID, $Reason)
    {
        $permsArray = collect(virtualLoginPermIds());
        if (!($permsArray->contains(Auth::user()->role_id)))
            return back()->with('error', 'Sanal giriş için geçerli bir yetkiye sahip değilsiniz!');


        if (str_word_count($Reason) < 3 || trim($Reason) == '')
            return back()->with('error', 'Gerekçe boş olamaz ve en az 3 kelimeden oluşmalıdır!');

        if ($UserID == Auth::id())
            return back()->with('error', 'Kendi hesabınıza sanal giriş yapamazsınız!');

        $user = User::where('id', $UserID)->first();
        if ($user->role_id == 1)
            return back()->with('error', 'Genel Yönetici yetkisine sahip hesaplara sanal giriş yapamazsınız!');


        $firstname = Auth::user()->name_surname;
        $role = Roles::where('id', Auth::user()->role_id)->first();

        $properties = [
            'Sanal Giriş Yapan Kullanıcı' => $firstname,
            'Yetkisi' => $role->display_name,
            'Gerekçesi' => $Reason,
            'Giriş Yapılan Hesap' => $user->name_surname,
            'Yetkisi' => $role->display_name
        ];
        activity()
            ->withProperties($properties)
            ->performedOn($user)
            ->inLog('Virtual Login')
            ->log($firstname . '(' . $role->display_name . ') tarafından ' . $user->name_surname . ' kullanıcısının hesabına sanal giriş yapıldı.');

        Session::put('virtual-login', Auth::id());
        Auth::loginUsingId($UserID);
        User::find($UserID)
            ->notify(new GeneralNotify('Hesabınıza ' . $firstname . ' (' . $role->display_name . ') tarafından [' . $Reason . '] gerekçesiyle sanal giriş yapıldı.', '#'));

        $properties = [
            'Sanal Giriş Yapan Kullanıcı' => $firstname,
            'Yetkisi' => $role->display_name,
            'Gerekçesi' => $Reason,
            'Giriş Yapılan Hesap' => $user->name_surname,
            'Yetkisi' => $role->display_name
        ];
        activity()
            ->withProperties($properties)
            ->performedOn($user)
            ->inLog('Virtual Login')
            ->log($firstname . '(' . $role->display_name . ') tarafından ' . $user->name_surname . ' kullanıcısının hesabına sanal giriş yapıldı.');

        $route = getUserFirstPage();
        return redirect()->intended(route($route))->with('success', 'Hoşgeldin ' . Auth::user()->name_surname);

    }


}
