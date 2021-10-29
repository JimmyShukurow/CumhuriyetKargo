<?php

namespace App\Http\Controllers\Backend\Personel;

use App\Http\Controllers\Controller;
use App\Models\Agencies;
use App\Models\RegioanalDirectorates;
use App\Models\RegionalDistricts;
use App\Models\TransshipmentCenters;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class PersonelController extends Controller
{
    public function lastLogs()
    {
        GeneralLog('Hesap hareketlerim sayfası görüntülendi.');
        $data['agencies'] = Agencies::all();
        return view('backend.personel.last_user_logs', compact('data'));
    }

    public function getUserLastLogs(Request $request)
    {
        $user_id = Decrypte4x($request->token);

        $startDate = $request->has('start_date') ? $request->start_date . ' 00:00:00' : date('Y-m-d') . ' 00:00:00';
        $finish_date = $request->has('finish_date') ? $request->finish_date . ' 23:59:59' : date('Y-m-d') . ' 23:59:59';

        if (($request->name_surname && $request->name_surname != '')) {
            $logs = DB::table('view_user_log_detail')
                ->whereRaw("created_at between '" . $startDate . "'  and '" . $finish_date . "'")
                ->whereRaw("name_surname like '%" . $request->name_surname . "%'")
                ->whereIn("log_name", UsersLogNames())
                ->whereRaw('causer_id = ' . $user_id);
        } else {
            $logs = DB::table('view_user_log_detail')
                ->whereRaw("created_at between '" . $startDate . "'  and '" . $finish_date . "'")
                ->whereIn("log_name", UsersLogNames())
                ->whereRaw('causer_id = ' . $user_id);
        }
        return datatables()->of($logs)
            ->setRowId(function ($log) {
                return "logs-item-" . $log->id;
            })
            ->editColumn('agency', function ($logs) {
                return $logs->branch_city . '/' . $logs->branch_district . '-' . $logs->branch_name;
            })
            ->addColumn('properties', 'backend.users.gm.columns.properties')
            ->rawColumns(['properties'])
            ->make(true);
    }

    public function accountSettings()
    {

        GeneralLog('Hesap ayarlarım sayfası görüntülendi.');
        $person = DB::table('view_users_all_info')
            ->where('id', Auth::id())
            ->first();

        $region = DB::table('regional_districts')
            ->where('city', $person->branch_city)
            ->where('district', $person->branch_district)
            ->first();

        $region_info = RegioanalDirectorates::where('id', $region->region_id)
            ->first();
        $rd_director = User::where('id', $region_info->director_id)->first();
        $rd_assistant_director = User::where('id', $region_info->assistant_director_id)->first();

        $agency = Agencies::where('id', $person->agency_code)
            ->first();

        $agency_director = User::where('agency_code', $person->agency_code)
            ->where('role_id', 20)
            ->first();

        $tc = DB::table('transshipment_center_districts')
            ->join('transshipment_centers', function ($join) {
                $join->on('transshipment_center_districts.tc_id', '=', 'transshipment_centers.id');
            })
            ->where('transshipment_center_districts.city', $agency->city)
            ->where('transshipment_center_districts.district', $agency->district)
            ->select('transshipment_centers.*')
            ->first();

//        $tc = TransshipmentCenters::where('id', $agency->transshipment_center_code)->first();
        $tc_director = User::where('id', $tc->tc_director_id)->first();
        $tc_assistant_director = User::where('id', $tc->tc_assistant_director_id)->first();


        return view('backend.personel.account_settings', compact([
            'person', 'region_info', 'agency', 'agency_director', 'tc', 'tc_director',
            'tc_assistant_director', 'rd_director', 'rd_assistant_director'
        ]));
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'password' => 'required',
            'passwordNew' => 'required|regex:/(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}/u',
            'passwordNewAgain' => 'required|same:passwordNew'
        ]);

        $password_check = User::where('id', Auth::id())
            ->where("password", Hash::make($request->password))
            ->first();

        if (Hash::check($request->password, Auth::user()->password)) {

            $update = User::find(Auth::id())
                ->update([
                    'password' => Hash::make($request->passwordNew)
                ]);

            if ($update) {
                GeneralLog('Şifre değişikliği yapıldı.');
                return back()->with('success', 'Şifreniz başarıyla değiştirildi!');
            } else {
                return back()->with('error', 'Bir hata oluştu, lütfen daha sonra tekrar deneyin!');
            }

        } else {
            $request->flash();
            return back()->with('error', 'Eski şifreniz hatalı!');
        }
    }

    public function markAsRead(Request $request)
    {
        Auth::user()
            ->unreadNotifications
            ->when($request->id, function ($query) use ($request) {
                return $query->where('id', $request->id);
            })
            ->markAsRead();

        $count = Auth::user()->unReadnotifications->count();

        # return response()->noContent();

        return response()->json($count, 200);
    }

    public function notificationAndAnnouncements($tab = 'Notifications')
    {
        GeneralLog('Bildirimlerim sayfası görüntülendi.');
        $data['tickets'] = DB::table('tickets')
            ->join('departments', 'tickets.department_id', '=', 'departments.id')
            ->select('tickets.*', 'departments.department_name')
            ->orderBy('id', 'desc')
            ->where('user_id', Auth::id())
            ->paginate(10);

        $data['count'] = DB::table('tickets')
            ->join('departments', 'tickets.department_id', '=', 'departments.id')
            ->select('tickets.*', 'departments.department_name')
            ->orderBy('id', 'desc')
            ->where('user_id', Auth::id())
            ->count();

        $notifications = DB::table('notifications')
            ->where('notifiable_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('backend.personel.notifications_and_announcements', compact('tab', 'notifications'));
    }

    public function searchModule(Request $request)
    {
        $search = $request->searchTerm;

        $permissions = DB::table('role_permissions')
            ->orderBy('module_group_must')
            ->orderBy('module_must')
            ->whereRaw(" role_id = " . Auth::user()->role_id . " and (sub_name like '%" . $search . "%' or module_name like '%" . $search . "%')")
            ->limit(10)
            ->get();

        $data = [];

        foreach ($permissions as $key) {
            $data[] = [
                'ico' => $key->module_ico,
                'module_name' => $key->module_name,
                'sub_name' => $key->sub_name,
                'url' => route($key->link)
            ];
        }

        return $data;


        return response()->json($permissions, 200);
    }


}
