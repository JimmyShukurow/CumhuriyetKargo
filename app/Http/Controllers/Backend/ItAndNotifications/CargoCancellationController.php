<?php

namespace App\Http\Controllers\Backend\ItAndNotifications;

use App\Http\Controllers\Controller;
use App\Models\Agencies;
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
        GeneralLog('[Admin] Kargo iptalleri sayfası görüntülendi.');
        $data['regional_directorates'] = RegioanalDirectorates::all();
        $data['agencies'] = Agencies::orderBy('agency_name', 'asc')->get();

        return view('backend.it_and_notifications.cargo_cancellations.index', compact('data'));
    }

    public function getCancellations(Request $request)
    {
        $creatingStartDate = $request->has('creating_start_date') ? $request->creating_start_date . ' 00:00:00' : date('Y-m-d') . ' 00:00:00';
        $creatingFinishDate = $request->has('creating_finish_date') ? $request->creating_finish_date . ' 23:59:59' : date('Y-m-d') . ' 23:59:59';
        $lastProccessStartDate = $request->has('last_proccess_start_date') ? $request->last_proccess_start_date . ' 00:00:00' : date('Y-m-d') . ' 00:00:00';
        $lastProccessFinishDate = $request->has('last_proccess_finish_date') ? $request->last_proccess_finish_date . ' 23:59:59' : date('Y-m-d') . ' 23:59:59';
        $agency = $request->agency;
        $confirm = $request->confirm;
        $appointment_reason = $request->appointment_reason;
        $confirming_name_surname = $request->confirming_name_surname;
        $ctn = str_replace([' ', '_'], ['', ''], $request->ctn);
        $invoiceNumber = $request->invoiceNumber;
        $name_surname = $request->name_surname;
        $creating_date_filter = $request->creating_date_filter;
        $last_proccess_date_filter = $request->last_proccess_date_filter;
        $regionArray = $request->selected_region != '' ? $request->selected_region : 'null';

        $tickets = DB::table('view_cargo_cancellation_app_detail')
            ->select(['view_cargo_cancellation_app_detail.*', 'cargoes.tracking_no', 'cargoes.invoice_number', 'cargoes.id as cargo_id', 'view_agency_region.regional_directorates', 'view_agency_region.agency_name'])
            ->join('view_agency_region', 'view_agency_region.id', '=', 'view_cargo_cancellation_app_detail.agency_code')
            ->join('cargoes', 'cargoes.id', '=', 'view_cargo_cancellation_app_detail.cargo_id')
            ->whereRaw($creating_date_filter == 'true' ? "view_cargo_cancellation_app_detail.created_at between '" . $creatingStartDate . "'  and '" . $creatingFinishDate . "'" : '1 > 0')
            ->whereRaw($last_proccess_date_filter == 'true' ? "view_cargo_cancellation_app_detail.approval_at between '" . $lastProccessStartDate . "'  and '" . $lastProccessFinishDate . "'" : '1 > 0')
            ->whereRaw($invoiceNumber ? "cargoes.invoice_number='" . $invoiceNumber . "'" : '1 > 0')
            ->whereRaw($invoiceNumber ? "cargoes.invoice_number='" . $invoiceNumber . "'" : '1 > 0')
            ->whereRaw($confirm != '' ? "view_cargo_cancellation_app_detail.confirm='" . $confirm . "'" : '1 > 0')
            ->whereRaw($agency ? 'view_agency_region.id  =' . $agency . '' : '1 > 0')
            ->whereRaw($appointment_reason ? 'view_cargo_cancellation_app_detail.application_reason like \'%' . $appointment_reason . '%\'' : '1 > 0')
            ->whereRaw($name_surname ? 'view_cargo_cancellation_app_detail.name_surname like \'%' . $name_surname . '%\'' : '1 > 0')
            ->whereRaw($confirming_name_surname ? 'view_cargo_cancellation_app_detail.confirming_user_name_surname like \'%' . $confirming_name_surname . '%\'' : '1 > 0')
            ->whereRaw('view_agency_region.regional_directorate_id in (' . $regionArray . ')');

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
            ->addColumn('invoice_number', 'backend.it_and_notifications.cargo_cancellations.columns.invoice_number')
            ->rawColumns(['application_reason', 'invoice_number', 'confirm', 'tracking_no', 'name_surname', 'redirected'])
            ->make(true);
    }

    public function pageRowCount(Request $request)
    {
        $creatingStartDate = $request->has('creating_start_date') ? $request->creating_start_date . ' 00:00:00' : date('Y-m-d') . ' 00:00:00';
        $creatingFinishDate = $request->has('creating_finish_date') ? $request->creating_finish_date . ' 23:59:59' : date('Y-m-d') . ' 23:59:59';
        $lastProccessStartDate = $request->has('last_proccess_start_date') ? $request->last_proccess_start_date . ' 00:00:00' : date('Y-m-d') . ' 00:00:00';
        $lastProccessFinishDate = $request->has('last_proccess_finish_date') ? $request->last_proccess_finish_date . ' 23:59:59' : date('Y-m-d') . ' 23:59:59';
        $agency = $request->agency;
        $confirm = $request->confirm;
        $appointment_reason = $request->appointment_reason;
        $confirming_name_surname = $request->confirming_name_surname;
        $ctn = str_replace([' ', '_'], ['', ''], $request->ctn);
        $name_surname = $request->name_surname;
        $creating_date_filter = $request->creating_date_filter;
        $last_proccess_date_filter = $request->last_proccess_date_filter;
        $regionArray = $request->selected_region != '' ? $request->selected_region : 'null';

        $tickets['confirmed'] = DB::table('view_cargo_cancellation_app_detail')
            ->select(['view_cargo_cancellation_app_detail.*', 'cargoes.tracking_no', 'cargoes.id as cargo_id', 'view_agency_region.regional_directorates', 'view_agency_region.agency_name'])
            ->join('view_agency_region', 'view_agency_region.id', '=', 'view_cargo_cancellation_app_detail.agency_code')
            ->join('cargoes', 'cargoes.id', '=', 'view_cargo_cancellation_app_detail.cargo_id')
            ->whereRaw($creating_date_filter == 'true' ? "view_cargo_cancellation_app_detail.created_at between '" . $creatingStartDate . "'  and '" . $creatingFinishDate . "'" : '1 > 0')
            ->whereRaw($last_proccess_date_filter == 'true' ? "view_cargo_cancellation_app_detail.approval_at between '" . $lastProccessStartDate . "'  and '" . $lastProccessFinishDate . "'" : '1 > 0')
            ->whereRaw($ctn ? 'cargoes.tracking_no=' . $ctn : '1 > 0')
            ->whereRaw($confirm != '' ? "view_cargo_cancellation_app_detail.confirm='" . $confirm . "'" : '1 > 0')
            ->whereRaw($agency ? 'view_agency_region.agency_name like \'%' . $agency . '%\'' : '1 > 0')
            ->whereRaw($appointment_reason ? 'view_cargo_cancellation_app_detail.application_reason like \'%' . $appointment_reason . '%\'' : '1 > 0')
            ->whereRaw($name_surname ? 'view_cargo_cancellation_app_detail.name_surname like \'%' . $name_surname . '%\'' : '1 > 0')
            ->whereRaw($confirming_name_surname ? 'view_cargo_cancellation_app_detail.confirming_user_name_surname like \'%' . $confirming_name_surname . '%\'' : '1 > 0')
            ->whereRaw('view_agency_region.regional_directorate_id in (' . $regionArray . ')')
            ->where('view_cargo_cancellation_app_detail.confirm', '1')
            ->count();

        $tickets['rejected'] = DB::table('view_cargo_cancellation_app_detail')
            ->select(['view_cargo_cancellation_app_detail.*', 'cargoes.tracking_no', 'cargoes.id as cargo_id', 'view_agency_region.regional_directorates', 'view_agency_region.agency_name'])
            ->join('view_agency_region', 'view_agency_region.id', '=', 'view_cargo_cancellation_app_detail.agency_code')
            ->join('cargoes', 'cargoes.id', '=', 'view_cargo_cancellation_app_detail.cargo_id')
            ->whereRaw($creating_date_filter == 'true' ? "view_cargo_cancellation_app_detail.created_at between '" . $creatingStartDate . "'  and '" . $creatingFinishDate . "'" : '1 > 0')
            ->whereRaw($last_proccess_date_filter == 'true' ? "view_cargo_cancellation_app_detail.approval_at between '" . $lastProccessStartDate . "'  and '" . $lastProccessFinishDate . "'" : '1 > 0')
            ->whereRaw($ctn ? 'cargoes.tracking_no=' . $ctn : '1 > 0')
            ->whereRaw($confirm != '' ? "view_cargo_cancellation_app_detail.confirm='" . $confirm . "'" : '1 > 0')
            ->whereRaw($agency ? 'view_agency_region.agency_name like \'%' . $agency . '%\'' : '1 > 0')
            ->whereRaw($appointment_reason ? 'view_cargo_cancellation_app_detail.application_reason like \'%' . $appointment_reason . '%\'' : '1 > 0')
            ->whereRaw($name_surname ? 'view_cargo_cancellation_app_detail.name_surname like \'%' . $name_surname . '%\'' : '1 > 0')
            ->whereRaw($confirming_name_surname ? 'view_cargo_cancellation_app_detail.confirming_user_name_surname like \'%' . $confirming_name_surname . '%\'' : '1 > 0')
            ->whereRaw('view_agency_region.regional_directorate_id in (' . $regionArray . ')')
            ->where('view_cargo_cancellation_app_detail.confirm', '-1')
            ->count();

        $tickets['waiting'] = DB::table('view_cargo_cancellation_app_detail')
            ->select(['view_cargo_cancellation_app_detail.*', 'cargoes.tracking_no', 'cargoes.id as cargo_id', 'view_agency_region.regional_directorates', 'view_agency_region.agency_name'])
            ->join('view_agency_region', 'view_agency_region.id', '=', 'view_cargo_cancellation_app_detail.agency_code')
            ->join('cargoes', 'cargoes.id', '=', 'view_cargo_cancellation_app_detail.cargo_id')
            ->whereRaw($creating_date_filter == 'true' ? "view_cargo_cancellation_app_detail.created_at between '" . $creatingStartDate . "'  and '" . $creatingFinishDate . "'" : '1 > 0')
            ->whereRaw($last_proccess_date_filter == 'true' ? "view_cargo_cancellation_app_detail.approval_at between '" . $lastProccessStartDate . "'  and '" . $lastProccessFinishDate . "'" : '1 > 0')
            ->whereRaw($ctn ? 'cargoes.tracking_no=' . $ctn : '1 > 0')
            ->whereRaw($confirm != '' ? "view_cargo_cancellation_app_detail.confirm='" . $confirm . "'" : '1 > 0')
            ->whereRaw($agency ? 'view_agency_region.agency_name like \'%' . $agency . '%\'' : '1 > 0')
            ->whereRaw($appointment_reason ? 'view_cargo_cancellation_app_detail.application_reason like \'%' . $appointment_reason . '%\'' : '1 > 0')
            ->whereRaw($name_surname ? 'view_cargo_cancellation_app_detail.name_surname like \'%' . $name_surname . '%\'' : '1 > 0')
            ->whereRaw($confirming_name_surname ? 'view_cargo_cancellation_app_detail.confirming_user_name_surname like \'%' . $confirming_name_surname . '%\'' : '1 > 0')
            ->whereRaw('view_agency_region.regional_directorate_id in (' . $regionArray . ')')
            ->where('view_cargo_cancellation_app_detail.confirm', '0')
            ->count();

        return $tickets;

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

            if ($app->confirm == '1')
                $delete = Cargoes::find($cargo->id)
                    ->delete();

            return response()
                ->json(['status' => 1], 200);


        } else
            return response()
                ->json(['status' => -1, 'Bir hata oluştu, lütfen daha sonra tekrar deneyiniz!'], 200);

        return $request->all();
    }


    public function backupCargo(Request $request)
    {
        // $cargo = Cargoes::find($request->id);
        $app = CargoCancellationApplication::find($request->id);

        $cargo = DB::table('cargoes')
            ->where('id', $app->cargo_id)
            ->first();

        $statement = Cargoes::onlyTrashed()
            ->where('id', $app->cargo_id)
            ->restore();

        $appointment = CargoCancellationApplication::find($request->id)
            ->update([
                'confirm' => '-1',
                'description' => '### KARGO GERİ YÜKLENDİ ###',
                'confirming_user' => Auth::id(),
            ]);

        if ($statement)
            return response()
                ->json(['status' => 1], 200);
        else
            return response()
                ->json(['status' => -1], 200);

        return $request->all();
    }


}
