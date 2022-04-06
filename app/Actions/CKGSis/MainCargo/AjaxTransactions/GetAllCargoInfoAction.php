<?php

namespace App\Actions\CKGSis\MainCargo\AjaxTransactions;

use App\Models\CargoMovements;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;

class GetAllCargoInfoAction
{
    use AsAction;

    public function handle($request)
    {

        $data = GetCargoInfoAction::run($request);

        return response()
            ->json($data, 200);
        $data['cargo'] = DB::table('cargoes')
            ->where('id', $request->id)
            ->first();


        if ($data['cargo'] == null)
            return response()
                ->json(['status' => 0, 'message' => 'Kargo Bulunamadı!'], 200);

        $data['cargo']->tracking_no = TrackingNumberDesign($data['cargo']->tracking_no);
        $data['cargo']->distance = getDotter($data['cargo']->distance);

        $data['sender'] = DB::table('currents')
            ->select(['current_code', 'tckn', 'category'])
            ->where('id', $data['cargo']->sender_id)
            ->first();
        $data['sender']->current_code = CurrentCodeDesign($data['sender']->current_code);

        $data['movements'] = CargoMovements::with(['cargo', 'user.role'])
            ->where('cargo_id', $data['cargo']->id)
            ->orderBy('created_at')
            ->get();

        foreach ($data['movements'] as $key) {
            $format = Carbon::parse($key->created_at);
            $key->created_time = $format->format('Y-m-d H:m:s');
        }

        $data['movementsSecondary'] = DB::table('cargo_movements')
            ->selectRaw('cargo_movements.*, number_of_pieces,  cargo_movements.group_id as testmebitch, (SELECT Count(*) FROM cargo_movements where cargo_movements.group_id = testmebitch) as current_pieces')
            ->groupBy('group_id')
            ->join('cargoes', 'cargoes.tracking_no', '=', 'cargo_movements.ctn')
            ->where('ctn', '=', str_replace(' ', '', $data['cargo']->tracking_no))
            ->orderBy('created_at', 'asc')
            ->get();

        $data['receiver'] = DB::table('currents')
            ->select(['current_code', 'tckn', 'category'])
            ->where('id', $data['cargo']->receiver_id)
            ->first();

        $data['receiver']->current_code = CurrentCodeDesign($data['receiver']->current_code);

        $data['creator'] = User::with('role')
            ->select(['id', 'name_surname', 'role_id'])
            ->where('id', $data['cargo']->creator_user_id)
            ->withTrashed()
            ->first();

        $data['departure'] = DB::table('agencies')
            ->select(['agency_code', 'agency_name', 'city', 'district'])
            ->where('id', $data['cargo']->departure_agency_code)
            ->first();

        $data['departure_tc'] = DB::table('transshipment_centers')
            ->select(['city', 'tc_name'])
            ->where('id', $data['cargo']->departure_tc_code)
            ->first();

        $data['arrival'] = DB::table('agencies')
            ->select(['agency_code', 'agency_name', 'city', 'district'])
            ->where('id', $data['cargo']->arrival_agency_code)
            ->first();

        $data['arrival_tc'] = DB::table('transshipment_centers')
            ->select(['city', 'tc_name'])
            ->where('id', $data['cargo']->arrival_tc_code)
            ->first();

        $data['sms'] = DB::table('sent_sms')
            ->select('id', 'heading', 'subject', 'phone', 'sms_content', 'result', 'created_at')
            ->where('ctn', str_replace(' ', '', $data['cargo']->tracking_no))
            ->get();

        $data['add_services'] = DB::table('cargo_add_services')
            ->select(['service_name', 'price'])
            ->where('cargo_tracking_no', str_replace(' ', '', $data['cargo']->tracking_no))
            ->get();

        $data['cancellation_applications'] = DB::table('view_cargo_cancellation_app_detail')
            ->where('cargo_id', $data['cargo']->id)
            ->get();

        $data['part_details'] = DB::table('cargo_part_details')
            ->where('tracking_no', str_replace(' ', '', $data['cargo']->tracking_no))
            ->get();

        $data['official_reports'] = DB::table('view_official_reports_general_info')
            ->whereRaw("( cargo_invoice_number ='" . $data['cargo']->invoice_number . "' or  description like '%" . $data['cargo']->invoice_number . "%')")
            ->get();

        $data['status'] = 1;

        return response()
            ->json($data, 200);
    }
}
