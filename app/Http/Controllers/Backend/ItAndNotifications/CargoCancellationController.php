<?php

namespace App\Http\Controllers\Backend\ItAndNotifications;

use App\Http\Controllers\Controller;
use App\Models\CargoCancellationApplication;
use App\Models\Cargoes;
use App\Models\User;
use App\Notifications\TicketNotify;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\SubModules;
use App\Models\RegioanalDirectorates;
use Illuminate\Support\Facades\Validator;
use function Symfony\Component\String\b;

class CargoCancellationController extends Controller
{
    public function index()
    {
        GeneralLog('[Admin] Ticket görüntülendi.');

        $data['tickets'] = DB::table('tickets')
            ->join('departments', 'tickets.department_id', '=', 'departments.id')
            ->select('tickets.*', 'departments.department_name')
            ->orderBy('id', 'desc')
            ->paginate(50);

        $data['count'] = DB::table('tickets')
            ->join('departments', 'tickets.department_id', '=', 'departments.id')
            ->select('tickets.*', 'departments.department_name')
            ->orderBy('id', 'desc')
            ->count();

        $data['title'] = SubModules::where('link', 'admin.systemSupport.index')->first();

        ## departments for roles
        $data['departments'] = DB::table('department_roles')
            ->select(['departments.id', 'departments.department_name'])
            ->join('departments', 'department_roles.department_id', '=', 'departments.id')
            ->where('department_roles.role_id', Auth::user()->role_id)
            ->get();

        $data['regional_directorates'] = RegioanalDirectorates::all();

        $countRequestDepartment = DB::table('department_roles')
            ->where('role_id', Auth::user()->role_id)
            ->get();


        return view('backend.it_and_notifications.cargo_cancellations.index', compact('data'));
    }

    public function getCancellations(Request $request)
    {
        ## departments for roles
        $allPermDepartments = DB::table('department_roles')
            ->select(['department_roles.department_id'])
            ->where('role_id', Auth::user()->role_id)
            ->get()->pluck('department_id')->toArray();

        $startDate = $request->has('start_date') ? $request->start_date . ' 00:00:00' : date('Y-m-d') . ' 00:00:00';
        $finish_date = $request->has('finish_date') ? $request->finish_date . ' 23:59:59' : date('Y-m-d') . ' 23:59:59';
        $department_id = $request->department != '' ? [$request->department] : $allPermDepartments;
        $status = $request->status;
        $priority = $request->priority;
        $name_surname = $request->name_surname;
        $title = $request->title;
        $dateFilter = $request->date_filter;
        $regionArray = $request->selected_region != '' ? $request->selected_region : 'null';
        $user_id = Decrypte4x($request->x_token);
        $ticket_no = $request->ticket_no;
        $redirected = $request->redirected;

        $role = User::where('id', $user_id)->first();
        ## departments for roles
        $countRequestDepartment = DB::table('department_roles')
            ->where('role_id', $role->role_id)
            ->where('department_id', $department_id)
            ->get();

        if ($countRequestDepartment === null) {
            $department_id = -1;
            $department_id = [$department_id];
        }


        $tickets = DB::table('tickets')
            ->join('departments', 'tickets.department_id', '=', 'departments.id')
            ->join('view_users_all_info', 'tickets.user_id', '=', 'view_users_all_info.id')
            ->join('view_region_name_and_districts', function ($join) {
                $join->on('view_users_all_info.branch_city', '=', 'view_region_name_and_districts.city');
                $join->on('view_users_all_info.branch_district', '=', 'view_region_name_and_districts.district');
            })
            ->select([
                'tickets.*',
                'departments.department_name',
                'view_users_all_info.name_surname',
                'view_users_all_info.branch_city',
                'view_users_all_info.branch_district',
                'view_users_all_info.branch_name',
                'view_users_all_info.user_type',
                'view_users_all_info.display_name',
                'tickets.created_at',
                'view_region_name_and_districts.region_id'
            ])
            ->whereIn('tickets.department_id', $department_id)
            ->whereRaw($dateFilter == 'true' ? "tickets.created_at between '" . $startDate . "'  and '" . $finish_date . "'" : '1 > 0')
            ->whereRaw($status != '' ? 'tickets.status=\'' . $status . '\'' : '1 > 0')
            ->whereRaw($redirected != '' ? 'tickets.redirected=\'' . $redirected . '\'' : '1 > 0')
            ->whereRaw($ticket_no != '' ? 'tickets.id=' . $ticket_no : '1 > 0')
            ->whereRaw($priority != '' ? 'tickets.priority=\'' . $priority . '\'' : '1 > 0')
            ->whereRaw($name_surname != '' ? 'view_users_all_info.name_surname like \'%' . $name_surname . '%\'' : '1 > 0')
            ->whereRaw($title != '' ? 'tickets.title like \'%' . $title . '%\'' : '1 > 0')
            ->whereRaw('region_id in(' . $regionArray . ')');


        $tickets = DB::table('view_cargo_cancellation_app_detail')
            ->select(['view_cargo_cancellation_app_detail.*', 'cargoes.tracking_no', 'cargoes.id as cargo_id', 'view_agency_region.regional_directorates', 'view_agency_region.agency_name'])
            ->join('view_agency_region', 'view_agency_region.id', '=', 'view_cargo_cancellation_app_detail.agency_code')
            ->join('cargoes', 'cargoes.id', '=', 'view_cargo_cancellation_app_detail.cargo_id');

        return datatables()->of($tickets)
            ->editColumn('free', function ($key) {
                return '';
            })
            ->editColumn('tracking_no', function ($key) {
                return TrackingNumberDesign($key->tracking_no);
            })->editColumn('application_reason', function ($key) {
                return substr($key->application_reason, 0, 50);
            })->editColumn('name_surname', function ($key) {
                return $key->name_surname . ' (' . $key->display_name . ')';
            })->editColumn('confirming_user_name_surname', function ($key) {

                $name = $key->confirming_user_name_surname;
                $display_name = $key->confirming_user_display_name;

                $name = $name != '' ? $name : '';
                $display_name = $display_name != '' ? ' (' . $display_name . ')' : '';

                return $name . $display_name;
            })->editColumn('confirm', function ($key) {
                if ($key->confirm == '1')
                    return '<b class="text-success">Onaylandı</b>';
                else if ($key->confirm == '-1')
                    return '<b class="text-danger">Reddedildi</b>';
                else if ($key->confirm == '0')
                    return '<b class="text-info">Onay Bekliyor</b>';
            })
            ->editColumn('application_reason', 'backend.it_and_notifications.cargo_cancellations.columns.reason')
            ->editColumn('tracking_no', 'backend.it_and_notifications.cargo_cancellations.columns.tracking_no')
            ->rawColumns(['application_reason', 'confirm', 'tracking_no', 'name_surname', 'redirected'])
            ->make(true);
    }

    public function setCargoCancellationApplicationResult(Request $request)
    {
        $rules = [
            'id' => 'required',
            'result' => 'required|in:1,-1,0',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails())
            return response()->json(['status' => '0', 'errors' => $validator->getMessageBag()->toArray()], 200);


        $update = CargoCancellationApplication::find($request->id)
            ->update([
                'confirm' => $request->result,
                'confirming_user' => Auth::id(),
                'description' => $request->description,
                'approval_at' => DB::raw('CURRENT_TIMESTAMP')
            ]);

        if ($update) {

            $app = CargoCancellationApplication::find($request->id);
            $cargo = DB::table('cargoes')
                ->where('id', $app->cargo_id)
                ->first();

            if ($app->confirm == '1')
                $trResult = 'onaylandı';
            else if ($app->confirm == '-1')
                $trResult = 'reddedildi';

            if ($app->confirm != 0)
                # Notification
                User::find($app->user_id)
                    ->notify(new TicketNotify('"' . TrackingNumberDesign($cargo->tracking_no) . '"' . ' takip numaralı kargo için oluşturmuş olduğunuz iptal başvurusu ' . $trResult . '.', route('systemSupport.TicketDetails', $app->id), $app->id));

            if ($app->confirm == '1'){
                $delete = Cargoes::find($cargo->id)
                    ->delete();
            }


            return response()
                ->json(['status' => 1], 200);


        } else
            return response()
                ->json(['status' => -1, 'Bir hata oluştu, lütfen daha sonra tekrar deneyiniz!'], 200);

        return $request->all();
    }

}
